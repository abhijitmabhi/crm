<template>
  <table class="table-striped table table-sm table-responsive">
    <thead class="thead-dark">
    <tr>
      <th scope="col">SAM</th>
      <th scope="col">Pipeline</th>
      <th scope="col" style="white-space: nowrap">Kein GF</th>
      <th scope="col">Unbearbeitet</th>
      <th scope="col">Wiedervorlagen</th>
      <th scope="col" style="white-space: nowrap">Nicht Erreicht</th>
      <th scope="col" style="white-space: nowrap">Kein Interesse</th>
      <th scope="col">Agents</th>
      <th scope="col">Aktionen</th>
    </tr>
    </thead>
    <tbody>
      <expert-lead-stats-table-row
          v-for="(rowData, index) in stats" :key="`row-${index}`"
          :route-pipeline-config="routePipelineConfig"
          :route-location-config="routeLocationConfig"
          :route-area-config="routeAreaConfig"
          :route-stats="routeStats"
          :expert="rowData"
      ></expert-lead-stats-table-row>
    </tbody>
  </table>
</template>

<script>
export default {
  name: "ExpertLeadStatsComponent",
  props: {
    stats: {
      type: Array,
      required: true
    },
    routePipelineConfig: {
      type: String,
      required: true
    },
    routeLocationConfig: {
      type: String,
      required: true
    },
    routeAreaConfig: {
      type: String,
      required: true
    },
    routeStats: {
      type: String,
      required: true
    },
  },
}
</script>

<style scoped>

</style>