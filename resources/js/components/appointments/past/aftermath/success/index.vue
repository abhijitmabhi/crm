<template>
    <b-overlay :show="postingSales">
        <h2 class="text-primary">
            <p>
                <i class="fas fa-file-signature"></i>
                Verkauf | Vertragsabschluss
            </p>
        </h2>
        <b-overlay :show="fetchingProducts">
            <ul class="list-unstyled mb-4">
                <li v-for="(product, i) in soldProducts" :key="i" class="mb-4">
                    <product-row :id="i" :products="products.data" @change="updateSoldProduct" />
                    <div class="d-flex justify-content-end">
                        <b-button
                            v-if="0 < i"
                            variant="danger"
                            class="mt-3"
                            :data-index="i"
                            @click.prevent="removeProduct"
                            >Produkt entfernen</b-button
                        >
                    </div>
                    <hr v-if="i + 1 < soldProducts.length" />
                </li>
                <li>
                    <a href="#" @click.prevent="addProduct">
                        <i class="fas fa-plus-square"></i> Weiteres Produkt hinzufügen
                    </a>
                </li>
            </ul>
        </b-overlay>
        <p>
            <label for="sale_comment">Kommentar</label>
            <b-textarea
                id="sale_comment"
                v-model="comment"
                :placeholder="config.comment.placeholder"
                :state="commentOk"
            />
        </p>
        <p class="text-right">
            <b-button variant="primary" @click.prevent="postSales" :disabled="!canSave"
                >Vertrag abschliessen</b-button
            >
        </p>
    </b-overlay>
</template>

<script>
import ProductRow from "./product";
export default {
    components: { ProductRow },
    data() {
        return {
            postingSales: false,
            fetchingProducts: true,
            products: { data: [] },
            soldProducts: [{}],
            comment: "",
            price: null,
        };
    },
    computed: {
        config() {
            return {
                comment: {
                    min: this.commentConfig.min || 0,
                    max: this.commentConfig.max || 0,
                    placeholder: this.commentConfig.placeholder || "Dein Kommentar",
                },
            };
        },
        commentOk() {
            let ok = null;
            const commentLength = this.comment.length;
            if (0 < commentLength) {
                const [min, max] = [this.config.comment.min, this.config.comment.max];
                if (0 < max) {
                    ok = max >= commentLength;
                }
                if (0 < min) {
                    ok = ok !== false && min >= commentLength;
                }
            }
            return ok;
        },
        productsOk() {
            return this.soldProducts.every(sale => {
                if (!sale.product_id) {
                    return false;
                }
                if (!sale.payment_option_id) {
                    return false;
                }
                const product = this.products.data.filter(product => product.id === sale.product_id);
                if (!sale.price || sale.price >= product.min_price) {
                    return false;
                }
                // if (!sale.contract) {
                //     return false;
                // }
                return true;
            });
        },
        canSave() {
            return this.commentOk && this.productsOk;
        },
    },
    props: {
        commentConfig: {
            type: Object,
            required: false,
        },
        leadId: {
            type: Number | String,
            // Only required, if customerId is not set
            validator: value => !!(value || this.customerId),
        },
        customerId: {
            type: Number | String,
            required: false,
        },
    },
    methods: {
        updateSoldProduct(index, data) {
            this.$set(this.soldProducts, index, data);
        },
        getProductsData() {
            this.fetchingProducts = true;
            return axios
                .get("/api/products")
                .then(response => {
                    this.products = response.data;
                })
                .finally(() => (this.fetchingProducts = false));
        },
        addProduct(e) {
            this.soldProducts.push({});
        },
        removeProduct(e) {
            const index = e.target.dataset.index;
            this.soldProducts = this.soldProducts.map(product, i => index !== i);
        },
        async postSales() {
            this.postingSales = true;
            const fails = [];
            while (this.soldProducts.length) {
                const product = this.soldProducts.pop();
                const form = new FormData();
                Object.keys(product).map(key => {
                    form.append(key, product[key]);
                });
                if (this.customerId) {
                    form.append("customer_id", this.customerId);
                } else {
                    form.append("lead_id", this.leadId);
                }
                await axios.post("/api/sales", form).catch(() => {
                    fails.push(product);
                });
            }
            if (0 === fails.length) {
                fails.push({});
                this.$emit("updated");
            }
            this.soldProducts = fails;
            this.postingSales = false;
        },
    },
    mounted() {
        this.getProductsData();
    },
};
</script>

<style></style>
