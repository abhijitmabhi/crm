<template>
    <div>
        <b-alert
            variant="success"
            :show="showSuccess"
            @dismissed="showSuccess=0"
            dismissible
        >Zahlungmethode wurde erfolgreich aktualisiert</b-alert>
        <b-alert
            variant="danger"
            :show="showFailure"
            @dismissed="showFailure=0"
            dismissible
        >Die Zahlungmethode konnte nicht aktualisiert werden</b-alert>
        <payment-option-form @submit="submit" @reset="reset" :form-data="readyFormData" ref="form"></payment-option-form>
    </div>
</template>
<script>
import { clone } from "lodash";
export default {
    data() {
        return {
            form: this.parseObject(this.formData),
            orginal: this.parseObject(this.formData),
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
                    rates:
                        '{ "once": true, "months": "0", "years": "0" }'
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
            if (typeof obj.rates == "string") {
                obj.rates = JSON.parse(obj.rates);
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
                if (key === "rates") {
                    value = JSON.parse(value);
                }
                obj[key] = value;
            });
            return obj;
        }
    }
};
</script>