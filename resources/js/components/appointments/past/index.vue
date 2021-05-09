<template>
    <b-table :items="items" :fields="fields" head-variant="dark" striped small responsive>
        <template v-slot:cell(aftermath)="data">
            <div class="text-center">
                <b-button variant="primary" v-b-modal="`modal_lead_${data.item.id}`">
                    <i class="fas fa-edit"></i>
                </b-button>
                <b-modal :id="`modal_lead_${data.item.id}`" hide-footer size="xl">
                    <appointment-aftermath :lead-id="data.item.id" @updated="reloadPage" />
                </b-modal>
            </div>
        </template>
    </b-table>
</template>

<script>
import appointmentAftermath from "./aftermath";
export default {
    components: { appointmentAftermath },
    data() {
        return {
            fields: [
                { key: "company_name", label: "Lead" },
                { key: "closed_until", label: "Termin" },
                { key: "aftermath", label: "Abschließen" },
            ],
        };
    },
    props: {
        items: {
            type: Array,
            required: true,
        },
    },
    methods: {
        reloadPage() {
            window.location.reload();
        },
    },
};
</script>
