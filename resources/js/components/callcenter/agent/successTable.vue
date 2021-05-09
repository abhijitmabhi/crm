<template>
    <div>
        <b-table
            stripped
            size="sm"
            responsive="sm"
            :items="agents"
            :fields="labels"
            thead-class="thead-dark"
        >
            <template v-slot:cell(sum)="data">{{weekSum(data.item)}}</template>
            <template v-slot:cell(dialer)="data">
                <a href="#" @click.prevent="toggleDialer(data.item.id)">
                    <span class="text-green" v-if="data.item.dialer_active">
                         Dialerintegration aktiv
                    </span>
                    <span v-else>
                        Dialerintegration deaktiviert
                    </span>
                </a>
            </template>
            <template v-slot:cell(recalls)="data">
                <div>{{data.item.recalls}}</div>
                <a href="#" @click.prevent="displayModal" :data-id="data.item.id">Neu zuweisen</a>
            </template>
        </b-table>
        <b-modal
            ref="modal"
            :title="`Wiedervorlagen von ${activeAgentName} umverteilen`"
            ok-title="Zuteilen"
            cancel-title="Abbrechen"
        >
            <b-select v-model="selectedAgentId" v-if="activeAgent" class="mb-3">
                <option value="default">Bitte Agenten auswählen</option>
                <option
                    v-for="agent in nonActiveAgents"
                    :key="agent.id"
                    :value="agent.id"
                >{{agent.name}}</option>
            </b-select>
            <p>
                Zeitraum von:
                <flat-pickr v-model="from" />
            </p>
            <p>
                Zeitraum bis:
                <flat-pickr v-model="to" />
            </p>
            <p>
                Betroffene Leads:
                <span v-if="checkingLeads">
                    <b-spinner small />
                </span>
                <span v-else>{{affectedLeads}}</span>
            </p>
            <div slot="modal-footer">
                <b-button @click="hideModal">Abbrechen</b-button>
                <b-button
                    variant="primary"
                    @click="updateLeads"
                    :disabled="!(selectedAgent && affectedLeads)"
                >Zuweisen</b-button>
            </div>
        </b-modal>
    </div>
</template>

<script>
import { debounce, merge } from "lodash";
export default {
    data() {
        return {
            page: 1,
            debouncedCheckLeads: debounce(this.checkLeads, 200),
            from: new Date(),
            to: new Date(),
            showModal: false,
            checkingLeads: false,
            activeAgent: null,
            selectedAgentId: "default",
            affectedLeads: 0,
            labels: [
                "name",
                {
                    key: "recalls",
                    label: "Wiedervorlagen",
                    class: "text-center"
                },
                "dialer",
                "mo",
                { key: "tu", label: "Di" },
                { key: "we", label: "Mi" },
                { key: "th", label: "Do" },
                "fr",
                { key: "sum", label: "Gesamt", class: "text-center" }
            ]
        };
    },
    props: {
        agents: {
            type: Array,
            required: true,
            validator(input) {
                const requiredKeys = [
                    "id",
                    "name",
                    "mo",
                    "tu",
                    "we",
                    "th",
                    "fr"
                ];
                let ok = input.filter(item => {
                    let key,
                        found = [];
                    for (key in item) {
                        if (requiredKeys.includes(key)) {
                            found.push(key);
                        }
                    }
                    return found.length === requiredKeys.length;
                });
                return ok.length === input.length;
            }
        }
    },
    computed: {
        selectedAgent() {
            if (this.selectedAgentId) {
                return this.agents.find(
                    agent => agent.id == this.selectedAgentId
                );
            }
            return null;
        },
        nonActiveAgents() {
            if (this.activeAgent) {
                return this.agents.filter(
                    agent => agent.id !== this.activeAgent.id
                );
            }
            return this.agents;
        },
        activeAgentName() {
            return this.activeAgent ? this.activeAgent.name : "niemand";
        },
        query() {
            const before = this.$moment(this.to)
                .add(1, "days")
                .startOf("day")
                .format();
            const after = this.$moment(this.from)
                .subtract(1, "days")
                .endOf("day")
                .format();
            return {
                agent: this.activeAgent.id,
                filter: 3,
                before,
                after
            };
        }
    },
    watch: {
        from() {
            if (0 < this.$moment(this.from).diff(this.to)) {
                this.to = new Date(this.from);
            }
            this.debouncedCheckLeads();
        },
        to() {
            this.debouncedCheckLeads();
        }
    },
    methods: {
        toggleDialer(id) {
            axios.post('/api/users/' + id + '/toggle_dialer')
            .then(refreshFromJs());

        },
        hideModal() {
            this.$refs.modal.hide();
        },
        checkLeads() {
            const params = merge({ per_page: 1 }, this.query);
            this.checkingLeads = true;
            return axios.get("/api/leads", { params }).then(response => {
                this.affectedLeads = response.data.meta.total;
                this.checkingLeads = false;
            });
        },
        updateLeads() {
            const wiedervorlage =
                "Wiedervorlage" + (1 < this.affectedLeads ? "n" : "");
            this.$bvModal
                .msgBoxConfirm(
                    `Wollen Sie wirklich ${this.affectedLeads} ${wiedervorlage} von ${this.activeAgent.name} zu ${this.selectedAgent.name} verschieben?`,
                    {
                        title: `${this.affectedLeads} ${wiedervorlage} verschieben`,
                        size: "sm",
                        buttonSize: "sm",
                        headerClass: "p-2 border-bottom-0",
                        footerClass: "p-2 border-top-0"
                    }
                )
                .then(() => {
                    const data = merge(
                        { update: { agent_id: this.selectedAgentId } },
                        this.query
                    );
                    return axios.post("/api/leads/batch", data);
                })
                .then(response => {
                    return this.$bvModal.msgBoxOk(
                        `${response.data} Wiedervorlagen wurden ${this.selectedAgent.name} zugewiesen.`
                    );
                })
                .then(() => {
                    window.location.reload();
                });
        },
        weekSum(agent) {
            let sum = agent.mo || 0;
            sum += agent.tu || 0;
            sum += agent.we || 0;
            sum += agent.th || 0;
            sum += agent.fr || 0;
            return sum;
        },
        displayModal(e) {
            const agentId = e.target.dataset.id;
            this.showModal = true;
            this.activeAgent = this.agents.find(agent => agent.id == agentId);
            this.checkLeads();
            this.$refs.modal.show();
        }
    }
};
</script>
