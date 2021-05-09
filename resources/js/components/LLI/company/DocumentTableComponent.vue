<template>
    <div>
        <table class="table table-striped table-sm table-responsive-sm">
            <b-thead class="thead-dark">
                <b-tr>
                    <b-th scope="col">Nachricht</b-th>
                    <b-th class="text-center">Aktionen</b-th>
                </b-tr>
            </b-thead>
            <b-tbody v-if="hasData">
                <b-tr v-for="(log, index) in data.data" :key="index">
                    <b-td>{{log.message}}</b-td>
                    <b-td class="text-center">
                        <a :href="downloadUrl(log)" class="p-2">
                            <i class="fas fa-download"></i>
                        </a>
                    </b-td>
                </b-tr>
                <b-tr>
                    <b-td class="text-center" colspan="2">
                        <a :href="index" class="btn btn-white text-success border border-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </b-td>
                </b-tr>
            </b-tbody>
            <tbody-empty v-else-if="data" colspan="2">
                <b-tr v-if="createUrl">
                    <b-td colspan="2" class="text-center">
                        <a :href="index" class="btn btn-white text-success border border-success">
                            <i class="fas fa-plus"></i>
                        </a>
                    </b-td>
                </b-tr>
            </tbody-empty>
            <tbody-spinner v-else colspan="2" />
        </table>
        <pagination v-if="hasData" :data="data" @pagination-change-page="update" :limit="2" />
    </div>
</template>
<script>
export default {
    data() {
        return { data: null };
    },
    props: {
        companyId: {
            type: String | Number,
            required: true,
        },
        createUrl: {
            type: URL,
            required: false,
        },
    },
    computed: {
        hasData() {
            return !!(this.data && this.data.data && this.data.data.length);
        },
        url() {
            return `/api/companies/${this.companyId}/logs`;
        },
        index() {
            return `/companies/${this.companyId}/logs`;
        },
    },
    methods: {
        update(page = 1, per_page = 10) {
            const params = { per_page, page };
            return axios.get(this.url, { params }).then(response => {
                this.data = response.data;
            });
        },
        downloadUrl(object) {
            return this.index + `/${object.id}`;
        },
    },
    mounted() {
        this.update();
    },
};
</script>