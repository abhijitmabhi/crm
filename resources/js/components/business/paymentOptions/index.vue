<template>
    <div class="card d-flex align-items-center flex-column">
        <crud-table :row-data="rowData" read-url="/payment_options" :col-config="colConfig" class="mb-4"></crud-table>
        <pagination
            v-if="payment_options"
            :data="payment_options"
            @pagination-change-page="updateData"
            :limit="2"
        ></pagination>
        <p class="mb-0 w-100">
            <a
                href="/payment_options/create"
                role="button"
                class="btn btn-white border text-success font-weight-bolder"
            >Zahlungsoption hinzufügen</a>
        </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            payment_options: null,
            colConfig: { name: "Name"}
        };
    },
    computed: {
        rowData() {
            if (this.payment_options && this.payment_options.data) return this.payment_options.data;
            return null;
        }
    },
    methods: {
        updateData(page = 1, per_page = 10, sort_by = "created_at") {
            return axios
                .get("/api/payment_options", { params: { page, sort_by, per_page } })
                .then(response => {
                    this.payment_options = response.data;
                });
        }
    },
    mounted() {
        this.updateData();
    }
};
</script>