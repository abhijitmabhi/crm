<template>
  <div>
    <h3 class="my-4">Lead neuem SAM zuweisen</h3>
    <select2-ajax
        v-model="expert_id"
        data-url="/api/users"
        :search-callback="searchCallback"
        :result-callback="resultCallback"
    />
    <button class="btn btn-primary my-4" @click="reassign">Neu zuweisen</button>
  </div>
</template>

<script>
export default {
  props: {
    lead: null,
    experts: null,
  },
  data() {
    return {
      expert_id: null,
    };
  },
  methods: {
    reassign() {
      axios
          .put(this.route('api.leads.expert.change', [this.lead.id, this.expert_id]))
          .then(() => {
            this.$emit("lead-saved");
          })
          .catch(error => {
            //TODO: show error message
            console.log(error.response.data);
          });
    },
    searchCallback(params) {
      return {name: params.term, role: "expert"};
    },
    resultCallback(result) {
      return {
        results: result.data.map(({id, name}) => {
          let returnObj = {
            id,
            text: name,
          };
          return returnObj;
        }),
      };
    },
    handOverChange(...args) {
      this.$emit("change", ...args);
    },
  },
};
</script>
