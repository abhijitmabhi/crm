<template>
  <b-row v-if="isVisible">
    <b-col class="d-flex flex-column">
      <div class="pt-2 pb-2 pr-2">
        <h3>Lead einem Kunden zuweisen</h3>
        <p>Hier wird der Lead zu einem Kunden zugewiesen, damit Produkte verknüpft werden können.</p>
      </div>
      <b-button v-b-modal="modalId" class="w-25 border lead-customer-button">Zuweisung starten</b-button>
    </b-col>
    <b-modal v-if="lead" :id="modalId" :ref="modalId" size="lg" hide-footer hide-header>
      <lead-to-customer-modal :lead="lead" @abort="hideModal"/>
    </b-modal>
  </b-row>
</template>

<script>
import LeadToCustomerModal from "./LeadToCustomerModalComponent";

export default {
  components: {LeadToCustomerModal},
  computed: {
    /**
     * Prevents multiple modals with same id (hopefully)
     */
    modalId() {
      return "convert-lead-" + this.lead.id;
    },
    isVisible() {
      let allowedRoles = [this.$enums.role.ADMIN, this.$enums.role.LLI_MANAGER, this.$enums.role.MANAGER];
      return allowedRoles.filter((role) => this.hasRole(role)).length > 0;
    }
  },
  props: {
    lead: Object,
  },
  methods: {
    hideModal() {
      this.$refs[this.modalId].hide();
    },
  },
};
</script>

<style scoped>
.lead-customer-button {
  min-height: 87px;
  max-width: 252px;
  min-width: 102px;
}
</style>