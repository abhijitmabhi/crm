<template>
    <b-overlay v-if="items" :show="fetchingItems">
        <b-table :items="items.data" :fields="fields" head-variant="dark" small responsive striped>
            <template v-slot:cell(fix)="data">
                <div class="d-flex align-items-center">
                    <b-form-input v-model="changes[data.item.id]" list="experts" />
                    <b-button
                        class="p-2 ml-2"
                        :disabled="!changes[data.item.id]"
                        variant="success"
                        @click.prevent="updateLead"
                        :data-id="data.item.id"
                    >
                        <i class="fas fa-save" :data-id="data.item.id"></i>
                    </b-button>
                </div>
            </template>
        </b-table>
        <pagination :data="items" @pagination-change-page="getInvalidLeads" :limit="2" />
        <datalist id="experts">
            <option v-for="expert in experts" :key="expert.id">{{expert.name}}</option>
        </datalist>
    </b-overlay>
</template>

<script>
export default {
    data() {
        return {
            fetchingItems: true,
            changes: {},
            items: null,
            fields: [
                { key: "company_name", label: "Unternehmensname" },
                { key: "city", label: "Ort" },
                { key: "fix", label: "Korrigieren" }
            ],
            page: 1,
            max_page: 1,
            experts: []
        };
    },
    methods: {
        getInvalidLeads(page = 1) {
            this.fetchingItems = true;
            return axios
                .get("/api/leads", {
                    params: { expert: "invalid", per_page: 25, page }
                })
                .then(response => {
                    this.items = response.data;
                    this.page = response.data.meta.current_page;
                    return response;
                })
                .finally(() => (this.fetchingItems = false));
        },
        getExperts(page = 1) {
            return axios
                .get("/api/users", {
                    params: { role: "Expert", page, per_page: 100 }
                })
                .then(response => {
                    this.experts = response.data.data;
                    return response;
                });
        },
        updateLead(e) {
            const id = e.target.dataset.id;
            const expert = this.experts.filter(
                expert =>
                    expert.name.toLowerCase() === this.changes[id].toLowerCase()
            )[0];
            if (expert) {
                return axios
                    .put(`/api/leads/${id}`, { expert_id: expert.id })
                    .then(this.getInvalidLeads)
                    .catch(() => console.log("Fail :("));
            }
        }
    },
    mounted() {
        this.getInvalidLeads();
        this.getExperts();
    }
};
</script>

<style>
</style>