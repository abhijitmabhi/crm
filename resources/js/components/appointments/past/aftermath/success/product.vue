<template>
    <div>
        <b-row>
            <b-col>
                <p>
                    <label :for="`sale_${id}_product`">Produkt</label>
                    <b-select
                        :id="`sale_${id}_product`"
                        v-model="selectedProduct"
                        :options="productOptions"
                        label-field="Produkt"
                    />
                </p>
            </b-col>
            <b-col>
                <p>
                    <label :for="`sale_${id}_financing`">Finanzierung</label>
                    <b-select
                        :id="`sale_${id}_financing`"
                        v-model="selectedFinance"
                        :options="financeOptions"
                        label-field="Finanzierung"
                        :disabled="null === selectedProduct"
                    />
                </p>
            </b-col>
            <b-col>
                <p class="mb-0">
                    <label :for="`sale_${id}_price`">Preis in € (Brutto)</label>
                    <b-input type="number" :id="`sale_${id}_price`" v-model="price" :state="priceOk" />
                </p>
                <p><small>Mindestpreis: {{minPrice}} €</small></p>
            </b-col>
        </b-row>
        <b-row>
            <!-- <b-col>
                <label :for="`sale_${id}_files`">Vertragsunterlagen</label>
                <b-file :id="`sale_${id}_files`" v-model="files" accept="application/pdf" plain />
            </b-col> -->
        </b-row>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedProduct: null,
            selectedFinance: null,
            price: 0,
            files: null,
        };
    },
    props: {
        id: {
            type: String | Number,
            required: true,
        },
        products: {
            type: Array,
            required: true,
        },
    },
    watch: {
        selectedProduct(newV, oldV) {
            this.$emit("change", this.id, this.form);
        },
        selectedFinance() {
            this.$emit("change", this.id, this.form);
        },
        price() {
            this.$emit("change", this.id, this.form);
        },
        files() {
            this.$emit("change", this.id, this.form);
        },
        selectedObj() {
            this.price = this.selectedObj.min_price;
        },
    },
    computed: {
        selectedObj() {
            if(this.selectedProduct) {
                return this.products.filter(product => this.selectedProduct === product.id)[0];
            }
            return null;
        },
        priceOk() {
            if(this.selectedProduct) {
                return this.price >= this.selectedObj.min_price;
            }
            return null;
        },
        minPrice() {
            if(this.selectedProduct) {
                return this.selectedObj.min_price;
            }
            return 0;
        },
        productOptions() {
            return this.products.map(product => {
                return { value: product.id, text: product.name };
            });
        },
        financeOptions() {
            if (null !== this.selectedProduct) {
                return this.selectedObj.payment_options.map(option => {
                    return { text: option.name, value: option.id };
                });
            }
            return [];
        },
        form() {
            return {
                product_id: this.selectedProduct,
                payment_option_id: this.selectedFinance,
                price: this.price,
                contract: this.files,
            };
        },
    },
};
</script>

<style></style>
