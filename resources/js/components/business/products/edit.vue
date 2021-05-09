<template>
    <div>
        <b-alert
            variant="success"
            :show="showSuccess"
            @dismissed="showSuccess=0"
            dismissible
        >Produkt wurde erfolgreich aktualisiert</b-alert>
        <b-alert
            variant="danger"
            :show="showFailure"
            @dismissed="showFailure=0"
            dismissible
        >Das Produkt konnte nicht aktualisiert werden</b-alert>
        <product-form @submit="submit" @reset="reset" :form-data="readyFormData" ref="form"></product-form>
    </div>
</template>
<script>
import { clone } from "lodash";
export default {
    data() {
        return {
            form: this.formData,
            showSuccess: 0,
            showFailure: 0
        };
    },
    props: {
        formData: {
            type: Object,
            default: () => {
                return {
                    name: "",
                    description: "",
                    min_price: "",
                };
            }
        },
        backUrl: {
            type: String,
            required: false
        },
        updateUrl: {
            type: String,
            required: true
        },
        updateMethod: {
            type: String,
            default: "post"
        }
    },
    computed: {
        readyFormData() {
            let obj = clone(this.formData);
            if (typeof obj.payment_progression == "string") {
                obj.payment_progression = JSON.parse(obj.payment_progression);
            }
            return obj;
        }
    },
    methods: {
        // Event Handlers
        submit(data) {
            this.updateProduct(this.convertData(data))
                .then(response => {
                    this.orginal = this.parseObject(response.data);
                    this.reset(this.orginal);
                    this.showSuccess = 8;
                })
                .catch(response => (this.showFailure = 8));
        },
        reset(data = null) {
            if (data) {
                this.$refs.form.setOrginal(data);
            }
            this.$refs.form.reset();
        },
        // Helpers
        updateProduct(data) {
            return axios.post(this.updateUrl, data);
        },
        convertData(orginalData) {
            const data = new FormData();
            Object.keys(orginalData).forEach(key => {
                data.append(key, orginalData[key]);
            });
            data.append("_method", this.updateMethod);
            return data;
        },
        parseObject(input) {
            let obj = {};
            Object.keys(input).forEach(key => {
                let value = input[key];
                if (key == "payment_progression") {
                    value = JSON.parse(value);
                }
                obj[key] = value;
            });
            return obj;
        }
    }
};
</script>