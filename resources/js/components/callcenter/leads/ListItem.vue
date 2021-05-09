<template>
    <modal v-bind:showModal.sync="showModal">
        <template v-slot:toggelingElement>
            <a href="#" class="font-weight-bolder">{{ leadName }}</a>
        </template>
        <template>
            <lead-form-history :show-storno="true" :timer="false" :leadId="leadId" in-modal />
        </template>
    </modal>
</template>
<script>
export default {
    data() {
        return {
            showModal: false,
        };
    },
    props: {
        leadId: Number,
        leadName: String,
    },
};
</script>
