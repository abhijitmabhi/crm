<template>
    <div class="row py-2 align-items-center" style="border-top: 1px solid #E1E1E1;">
        <div class="col">
            {{customer.name}}
            {{customer.street}}
            {{customer.city}}
            {{customer.zip}}
        </div>
        <div class="col">
            {{expert.name}}
        </div>
        <div class="col">
            <p class="mb-0">{{product.name}}</p>
            <p class="mb-0">{{soldAt}}€ (Brutto)</p>
        </div>
        <div class="col" v-if="!accepted">
            <select v-model="payedWith">
                <option value="unset" disabled>Bitte auswählen</option>
                <option :value="option.id" v-for="option in product.payment_options">{{option.name}}</option>
            </select>
        </div>
        <div class="col" v-if="!accepted">
            <select @change="handleSale" v-model="action">
                <option value="unset" selected disabled>Bitte auswählen</option>
                <option value="approved">Provision freigeben</option>
                <option value="denied">Ablehnen</option>
            </select>
        </div>
        <div class="col" v-if="accepted">
            {{this.paymentOption.name}}
        </div>
    </div>

</template>
<script>
    export default {
        data() {
            return {
                payedWith: "unset",
                action: "unset"
            }
        },
        mounted(){
            if (typeof this.paymentOption === 'object') {
                this.payedWith = this.paymentOption.id
            }
        },
        methods: {
            handleSale(){
                if (this.payedWith === "unset" && this.action === "approved" ) {
                    this.$bvModal.msgBoxOk('Bitte zuerst Finanzierungsoption  wählen.');
                    this.action = "unset";
                    return
                }
                if(this.action === "approved") {
                    axios.patch(`/api/sales/${this.id}`, {
                        payed_with: this.payedWith,
                        action: this.action
                    }).then(response => {
                        refreshFromJs();
                    })
                }
                if(this.action === "denied") {
                    axios.patch(`/api/sales/${this.id}`,{
                        action: this.action
                    }).then(response => {
                        refreshFromJs();
                    })
                }
            }
        },
        props: {
            customer: {},
            expert: {},
            product: {},
            soldAt: {},
            id: {},
            paymentOption: '',
            accepted: false
        }
    }
</script>