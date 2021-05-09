<template>
  <lead-create-form
      :user-id="userId"
      :route-success="'/map'"
      :is-expert="isExpert"
      :all-categories="allCategories"
      :all-experts="allExperts"
      :form-data="formData"
  />
</template>

<script>
export default {
  name: "LeadFormPopupComponent",
  props: {
    userId: Number,
    isExpert: Boolean,
    apiPlaceData: Object
  },
  data() {
    return {
      allExperts: [],
      allCategories: [],
      formData: {}
    };
  },
  mounted: function() {
      axios.get("/api/categories/leads/string").then(response => {
        this.allCategories = response.data;
      });

      axios.get("/api/experts/").then(response => {
        this.allExperts = response.data;
      });

      this.initFormData();
    },

  methods: {
    initFormData: function () {
      let formData = Object.assign({}, this.apiPlaceData);

      formData.contact_name = null;
      formData.closed_until = null;
      formData.appointment_end = null;
      formData.appointment_comment = null;
      formData.email = "";
      formData.expert_status = 0;
      formData.status = 1;

      this.formData = formData;
    }
  }
}
</script>

<style scoped>

</style>