<template>
    <div>
        <b-table
            :items="leads.data"
            :fields="fields"
            :order="setOrderBy"
            striped
            small
            responsive
            head-variant="dark"
        >
            <template v-slot:cell(website)="data">
                <a
                    v-if="validURL(data.item.website)"
                    :href="ensureProtocol(data.item.website)"
                    target="_blank"
                >{{data.item.website}}</a>
                <span v-else>{{data.item.website}}</span>
            </template>
            <template v-slot:cell(test)="data">
                <div class="d-flex justify-content-between">
                    <b-button :data-index="data.index" @click="modalButtonClick">Fehler beheben</b-button>
                </div>
            </template>
        </b-table>
        <b-modal
            ref="invalidLeadModal"
            :hide-footer="true"
            id="invalid_lead_modal"
            :class="{ in: modalShown }"
            class="my-modal"
            size="full"
        >
            <div v-if="lead" class="d-flex justify-content-center">
                <lead-edit
                    edit
                    :lead="lead"
                    @abort="hideModal"
                    @update_lead="onUpdate"
                    save-button-text="Speichern und Weiter!"
                    show="false"
                />
                <iframe
                    v-if="lead.website"
                    :src="ensureProtocol(lead.website)"
                    width="100%"
                    height="900"
                    name="iFrame"
                    title="Das ist mein Video"
                ></iframe>
                <div
                    class="d-flex justify-content-center w-100"
                    v-else
                >Es ist keine Website angegeben</div>
            </div>
            <div class="d-flex justify-content-center" v-else>
                <b-spinner />
            </div>
        </b-modal>
        <div class="d-flex align-items-center justify-content-center">
            <pagination :data="leads" @pagination-change-page="getLeads" :limit="2"></pagination>
        </div>
    </div>
</template>

<script>
import { validURL } from "@utils/functions";
export default {
    data() {
        return {
            fields: [
                {
                    key: "company_name",
                    label: "Unternehmensname"
                },
                "website",
                {
                    key: "test",
                    label: "Fehler beheben"
                }
            ],
            loading: true,
            leads: {},
            lead: null,
            orderBy: "",
            selected: "",
            experts: {},
            expert: "",
            modalShown: false,
            index: null,
            page: null
        };
    },
    methods: {
        ensureProtocol(str) {
            if (/https?:\/\//.test(str)) {
                return str;
            }
            return "https://" + str;
        },
        validURL(str) {
            return validURL(str);
        },
        hideModal() {
            this.$refs.invalidLeadModal.hide();
        },
        onUpdate(promise) {
            this.lead = false;
            return promise
                .then(() => this.getLeads(this.page))
                .then(() => {
                    this.lead = this.leads.data[this.index];
                });
        },
        getLeads(page = 1) {
            this.page = page;
            return axios
                .get("/api/leads", {
                    params: {
                        page,
                        contact_name: "",
                        sort_by: "website",
                        sort_direction: "desc"
                    }
                })
                .then(response => {
                    this.leads = response.data;
                    this.loading = false;
                });
        },
        setOrderBy(value) {
            this.orderBy = value;
            this.getLeads();
        },
        async getExperts() {
            let res = await axios.get("/api/users?role=expert");
            this.experts = res.data.data;
        },
        modalButtonClick(e) {
            this.index = e.target.dataset.index;
            this.lead = this.leads.data[this.index];
            this.$refs.invalidLeadModal.show();
        }
    },
    mounted() {
        this.getLeads();
        this.getExperts();
    }
};
</script>
