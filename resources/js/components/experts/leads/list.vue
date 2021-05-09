<template>
    <div>
        <b-input v-model="filter" placeholder="Suchbegriff eingeben..." debounce="350" />
        <b-overlay :show="loading">
            <div>
                <b-table
                    small
                    striped
                    responsive="sm"
                    :items="leads"
                    :fields="fields"
                    :tbody-tr-class="rowClass"
                    :filter="filter"
                >
                    <template v-slot:cell(company_name)="data">
                        <a
                            @click.prevent="showModal"
                            href="#"
                            :data-id="data.item.id"
                        >{{data.item.company_name}}</a>
                    </template>
                    <template v-slot:cell(zip)="data">
                        <span>
                            {{data.item.street}}
                            <br />
                            {{data.item.zip}} {{data.item.city}}
                        </span>
                    </template>
                    <template v-slot:cell(phone1)="data">
                        <a
                            :href="'tel:'+nr"
                            v-for="nr in data.item.phone"
                            class="d-block"
                            :key="nr"
                        >{{nr}}</a>
                    </template>
                    <template v-slot:cell(status)="data">
                        <span>{{statusText(data.item)}}</span>
                    </template>
                    <template v-slot:cell(private)="data">
                        <div class="d-flex justify-content-center">
                            <input
                                type="checkbox"
                                v-model="data.item.private"
                                :data-id="data.item.id"
                                @change="changeLeadPrivacy"
                            />
                        </div>
                    </template>
                </b-table>
                <b-col v-if="leadData">
                    <p>
                        <small>Lead {{leadData.meta.from}} bis {{leadData.meta.to}} von {{leadData.meta.total}}</small>
                    </p>
                    <pagination :data="leadData" @pagination-change-page="update" :limit="2" />
                </b-col>
            </div>
        </b-overlay>
        <b-modal ref="leadFormModal" size="xl">
            <lead-form-history
                in-modal
                v-if="selectedLead"
                :expert-it="expertId"
                :lead-id="selectedLead"
                storno
            />
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            expertModalId: "expert-lead-modal",
            selectedLead: null,
            filter: "",
            leadData: null,
            fields: [
                { key: "private", label: "Geblockt" },
                { key: "company_name", sortable: true, label: "Name" },
                { key: "zip", sortable: true, label: "Adresse" },
                { key: "phone1", sortable: false, label: "Telefon" },
                { key: "status", sortable: true, label: "Status" },
                {
                    key: "last_interaction",
                    sortable: true,
                    label: "Letzte Interaktion",
                },
                { key: "created_at", sortable: true, label: "Erstellt" },
            ],
        };
    },
    props: {
        expertId: {
            type: Number | String,
            required: true,
        },
    },
    computed: {
        leads() {
            if (this.leadData && Array.isArray(this.leadData.data)) {
                return this.leadData.data;
            }
            return [];
        },
    },
    methods: {
        async update(page = 1) {
            this.loading = true;
            const params = { page };
            axios.get(`/api/experts/${this.expertId}/leads`, { params }).then(response => {
                this.leadData = response.data;
            });
            this.loading = false;
        },
        showModal(e) {
            this.selectedLead = e.target.dataset.id;
            this.$refs.leadFormModal.show();
        },
        rowClass(item, type) {
            switch (parseInt(item.status, 10)) {
                case 3:
                    return "table-warning";
                case 5:
                    return "table-info";
                case 6:
                    return "table-warning";
                case 7:
                    return "table-success";
                default:
                    return "";
            }
        },
        statusText(item) {
            switch (parseInt(item.status, 10)) {
                case 1:
                    return "Offen";
                case 2:
                    return "Nicht Erreicht";
                case 3:
                    return "Wiedervorlage";
                case 4:
                    return "Kein Interesse";
                case 5:
                    return `Terminiert (${item.next_appointment})`;
                case 6:
                    return "Blacklisted";
                case 7:
                    return "Kunde";
                default:
                    return "Unbekannter Status";
            }
        },
        changeLeadPrivacy(e) {
            axios.put(`/api/leads/${e.target.dataset.id}`, {
                blocked: e.target.checked ? 1 : 0,
            });
        },
    },
    mounted() {
        this.update().finally(() => (this.loading = false));
    },
};
</script>
