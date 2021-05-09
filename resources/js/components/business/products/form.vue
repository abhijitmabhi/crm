<template>
    <base-form @submit="emitSubmit" @reset="emitReset" :back-url="backUrl">
        <div class="row mb-4">
            <div class="col">
                <label for="name">Name</label>
                <input v-model="form.name" type="text" name="name" id="name" class="form-control" />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <label for="description">Beschreibung</label>
                <b-textarea v-model="form.description" rows="4" id="description"></b-textarea>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <label for="min_price">Mindestpreis</label>
                <b-input-group prepend="€">
                    <b-input
                        v-model="form.min_price"
                        type="number"
                        step="1"
                        name="min_price"
                        id="min_price"
                    />
                </b-input-group>
            </div>
        </div>
        <div class="row mb-4">
            <label for="name">Zahlungsoptionen</label>

            <select2-ajax
                data-url="/api/payment_options"
                :search-callback="paymentOptionsSearch"
                :result-callback="paymentOptionsResult"
                :multiple="true"
                @select="selectPaymentOptions"
            >
                <option
                    v-for="option in form.payment_options"
                    :value="option.payment_option_id"
                    selected
                    :key="option.payment_option_id"
                >{{option.name}}</option>
            </select2-ajax>
        </div>
    </base-form>
</template>
<script>
import { clone } from "lodash";
export default {
    data() {
        return {
            form: this.formData,
        };
    },
    props: {
        formData: {
            type: Object,
            required: true,
        },
        backUrl: {
            type: String,
            required: false,
        },
    },
    computed: {
        showMonthsConfig() {
            return this.selectedPaymentMethods.includes("months");
        },
        showYearsConfig() {
            return this.selectedPaymentMethods.includes("years");
        },
    },
    methods: {
        paymentOptionsSearch(param) {
            return {
                name: param.term,
            };
        },
        paymentOptionsResult(result) {
            return {
                results: result.data.map(option => {
                    return {
                        id: option.id,
                        text: option.name,
                    };
                }),
            };
        },
        selectPaymentOptions(options) {
            this.form.selected = options;
        },
        getData() {
            return clone(this.form);
        },
        emitSubmit() {
            this.$emit("submit", this.getData());
        },
        emitReset() {
            this.$emit("reset", this.getData());
        },
        reset() {
            this.form = clone(this.orginal);
        },
        setOrginal(update) {
            this.orginal = update;
        },
    },
};
</script>