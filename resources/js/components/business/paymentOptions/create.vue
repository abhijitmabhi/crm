<template>
    <div>
        <b-alert
            variant="success"
            :show="showSuccess"
            @dismissed="showSuccess=0"
            dismissible
        >Zahlungsmethode wurde erfolgreich aktualisiert</b-alert>
        <b-alert
            variant="danger"
            :show="showFailure"
            @dismissed="showFailure=0"
            dismissible
        >Die Zahlungsmethode konnte nicht aktualisiert werden</b-alert>
        <payment-option-form
            @submit="submit"
            @reset="reset"
            :form-data="defaultProduct"
            :back-url="backUrl"
            ref="form"
        ></payment-option-form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            showSuccess: 0,
            showFailure: 0,
            defaultProduct: {
                name: "",
                description: "",
                type: 'once',
                rates: 0
            }
        };
    },
    props: {
        storeUrl: {
            type: String,
            required: true
        },
        backUrl: {
            type: String,
            required: false
        }
    },
    methods: {
        submit(data) {
            this.storeProduct(this.convertData(data))
                .then(response => {
                    this.showSuccess = 8;
                    this.reset();
                })
                .catch(response => (this.showFailure = 8));
        },
        reset() {
            this.$refs.form.reset();
        },
        storeProduct(data) {
            return axios.post(this.storeUrl, data);
        },
        convertData(orginalData) {
            const data = new FormData();
            Object.keys(orginalData).forEach(key => {
                data.append(key, orginalData[key]);
            });
            return data;
        }
    }
};
</script>