<template>
  <div>
    <div class="form-group">
      <label for="selectedLocations">Problem Locations suchen</label>
      <input
          v-model="search"
          type="text"
          class="form-control"
          id="selectedLocations"
          placeholder="Name oder Adresse der Location eingeben"
      >
    </div>

    <problem-locations-table
        :selected-locations="filteredLocations"
        @informParentToSetOverlay="informGrandParentToSetOverlay()"
        @informParentToHideOverlay="informGrandParentToHideOverlay()"
    >
    </problem-locations-table>

  </div>
</template>
<script>
export default {
  name: "ProblematicLocations",
  props: {
    problemLocations: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      selectedLocations: [],
      search: '',
    };
  },
  methods: {
    informGrandParentToSetOverlay: function() {
      this.$emit('setOverlayWhileLoading');
    },
    informGrandParentToHideOverlay: function() {
      this.$emit('hideOverlay');
    }
  },
  computed: {
    filteredLocations: function () {
      let searchTerm = this.search;
      return this.problemLocations.filter(function (location) {
        return location.name.toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0 ||
            location.address.toLowerCase().indexOf(searchTerm.toLowerCase()) >= 0;
      });
    }
  }
}
</script>

<style scoped>

</style>