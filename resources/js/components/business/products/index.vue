<template>
    <div class="card d-flex align-items-center flex-column">
        <crud-table :row-data="rowData" read-url="/products" :col-config="colConfig" class="mb-4"></crud-table>
        <pagination
            v-if="products"
            :data="products"
            @pagination-change-page="updateData"
            :limit="2"
        ></pagination>
        <p class="mb-0 w-100">
            <a
                href="/products/create"
                role="button"
                class="btn btn-white border text-success font-weight-bolder"
            >Produkt hinzufügen</a>
        </p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: null,
            colConfig: { name: "Name", min_price: "Mindestpreis (€)" }
        };
    },
    computed: {
        rowData() {
            if (this.products && this.products.data) return this.products.data;
            return null;
        }
    },
    methods: {
        updateData(page = 1, per_page = 10, sort_by = "created_at") {
            return axios
                .get("/api/products", { params: { page, sort_by, per_page } })
                .then(response => {
                    this.products = response.data;
                });
        }
    },
    mounted() {
        this.updateData();
    }
};
</script>