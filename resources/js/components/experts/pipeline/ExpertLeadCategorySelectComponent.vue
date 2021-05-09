<template>
  <div class="form-group">
    <form method="POST" :action="routeSubmit" @submit.prevent="onSubmit">
      <input type="hidden" name="expert" :value="expertId">
      <slot><!-- CSRF gets injected into this slot --></slot>
      <label class="control-label" for="categories">{{labelText}}</label>
      <v-select
          id="categories"
          name="categories"
          multiple
          v-model="selectedCategories"
          :options="allCategories"
          placeholder="Branche auswählen"
      ></v-select>
      <button type="submit" class="btn btn-white border-secondary">Speichern</button>
    </form>
    <br>
    <category-stats
          :category-stats="categoryStats"
          :shown-categories="selectedCategories">
    </category-stats>
  </div>
</template>

<script>
export default {
  name: "ExpertLeadCategorySelectComponent",
  props: {
    routeSubmit: {
      type: String,
      required: true
    },
    routeSuccess: {
      type: String,
      required: true
    },
    labelText: {
      type: String,
      required: true
    },
    expertId: {
      type: Number,
      required: true
    },
    allCategories: {
      type: Array,
      default: []
    },
    expertCategories: {
      type: Array,
      default: []
    },
    categoryStats: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      selectedCategories: []
    }
  },
  created() {
    this.selectedCategories = this.expertCategories
  },
  methods: {
    onSubmit() {
      axios.post(this.routeSubmit, {
        "categories": this.selectedCategories,
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