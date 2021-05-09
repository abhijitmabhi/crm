<template>
    <div>
        <a href="#" v-b-modal.modal="modalId">{{leadName}}</a>
        <b-modal :id="modalId" size="xl">
            <lead-form :show-storno="true" :timer="false" :leadId="leadId" in-modal />
        </b-modal>
    </div>
</template>

<script>
export default {
    data() {
        return {
            showModal: false
        };
    },
    props: {
        leadId: Number,
        leadName: String
    },
    computed: {
        modalId() {
            return "leadModal" + this.leadId;
        }
    }
};
</script>
