<template>
  <div class="form-group">
    <form method="POST" :action="routeSubmit" @submit.prevent="onSubmit">
      <slot><!-- CSRF gets injected into this slot --></slot>
      <input type="hidden" name="expert" :value="expertId">
      <label class="control-label" for="agents">Zugewiesene Agents</label>
      <v-select
          id="agents"
          name="agents"
          multiple
          v-model="selectedAgents"
          :options="allAgents"
          label="name"
          placeholder="Call Agent auswählen">
      </v-select>
      <button type="submit" class="btn btn-white border-secondary">Speichern</button>
    </form>
  </div>
</template>

<script>
export default {
  name: "ExpertCallCenterAgentAssignmentComponent",
  props: {
    routeSubmit: {
      type: String,
      required: true
    },
    routeSuccess: {
      type: String,
      required: true
    },
    expertId: {
      type: Number,
      required: true
    },
    allAgents: {
      type: Array,
      default: []
    },
    expertAgents: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      selectedAgents: []
    }
  },
  created() {
    this.selectedAgents = this.expertAgents
  },
  methods: {
    onSubmit() {
      axios.post(this.routeSubmit, {
        "agentIds": this.selectedAgents.map((agent) => agent.id),
        "expertId": this.expertId
      }).then(() => {
        return window.location.href = this.routeSuccess.toString()
      });
    },
  }
}
</script>

<style scoped>

</style>