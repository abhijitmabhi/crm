<!--TODO: maybe a more general approach with a vue component that doesn't know and care about it's content.
    and will just show or hide a spinner depending on an emitted event. -->
<template>
  <div>
    <b-overlay :show="loading">
      <b-card header="Alle Kunden die Probleme aufweisen" header-class="simple-card-header" header-tag="h4">
        <problematic-locations
            :problem-locations="problemLocations"
            @setOverlayWhileLoading="setOverlay()"
            @hideOverlay="hideOverlay()"
        >
        </problematic-locations>
      </b-card>
    </b-overlay>
  </div>
</template>
<script>
export default {
  name: "ProblematicLocationsSpinnerComponent",
  props: {
    problemLocations: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      loading: false
    };
  },
  methods: {
    setOverlay: function() {
      this.loading = true;
    },
    hideOverlay: function() {
      this.loading = false;
    }
  }
}
</script>

<style scoped>

</style>