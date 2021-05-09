<template>
    <!-- Use <b-table> instead -->
    <table class="table table-striped table-sm table-responsive-sm">
        <b-thead class="thead-dark">
            <b-tr>
                <b-th v-for="(name, key) in colConfig" :key="key" scope="col">{{name}}</b-th>
                <b-th v-if="actionsAvailable" class="text-center">Aktionen</b-th>
            </b-tr>
        </b-thead>
        <b-tbody v-if="hasData">
            <b-tr v-for="(location, index) in data" :key="index">
                <b-td v-for="(name,key) in colConfig" :key="key">{{location[key]}}</b-td>
                <b-td class="text-center">
                    <a v-if="readUrl" :href="readUrl + '/' + location.id" class="p-2">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a v-if="editUrl" :href="editUrl + '/' + location.id" class="p-2 text-info">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a
                        v-if="deleteUrl"
                        :href="deleteUrl + '/' + location.id"
                        class="p-2 text-danger"
                    >
                        <i class="fas fa-stop"></i>
                    </a>
                </b-td>
            </b-tr>
            <b-tr v-if="createUrl">
                <b-td :colspan="colspan" class="text-center">
                    <a :href="createUrl" class="btn btn-white text-success border">
                        <i class="fas fa-plus"></i>
                    </a>
                </b-td>
            </b-tr>
        </b-tbody>
        <tbody-empty v-else-if="data" :colspan="colspan">
            <b-tr>
                <b-td :colspan="colspan" class="text-center">
                    <a :href="createUrl" class="btn btn-white text-success border">
                        <i class="fas fa-plus"></i>
                    </a>
                </b-td>
            </b-tr>
        </tbody-empty>
        <tbody-spinner v-else-if="fetching" :colspan="colspan" />
        <tbody-empty v-else colspan="2" />
    </table>
</template>
<script>
import { clone } from "lodash";
export default {
    data() {
        return {
            data: null,
            fetching: false,
        };
    },
    props: {
        rowData: {
            type: Array | Object | String,
            required: true,
        },
        colConfig: {
            type: Object,
            required: true,
        },
        createUrl: {
            type: String,
            default: "",
        },
        readUrl: {
            type: String,
            default: "",
        },
        editUrl: {
            type: String,
            default: "",
        },
        deleteUrl: {
            type: String,
            default: "",
        },
    },
    computed: {
        actionsAvailable() {
            return this.editUrl || this.readUrl || this.deleteUrl;
        },
        colspan() {
            if (this.actionsAvailable) {
                return Object.keys(this.colConfig).length + 1;
            } else {
                return Object.keys(this.colConfig).length;
            }
        },
        hasData() {
            return !!(this.data && this.data.length);
        },
    },
    watch: {
        rowData() {
            this.update();
        },
    },
    methods: {
        update() {
            switch (typeof this.rowData) {
                case "string":
                    this.getData(this.rowData);
                    break;
                default:
                    this.data = clone(this.rowData);
                    break;
            }
        },
        getData(url) {
            this.fetching = true;
            return axios
                .get(url)
                .then(response => {
                    if (Array.isArray(response.data)) this.data = response.data;
                    else this.data = response.data.data;
                })
                .finally(() => (this.fetching = false));
        },
    },
    mounted() {
        this.update();
    },
};
</script>