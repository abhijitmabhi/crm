<template>
  <tr>
    <td>
      <button class="btn btn-link" style="text-align: left" v-on:click="onClickPipelineConfig(expert.id)">
        <em v-if="expert.deleted_at || !expert.is_active || expert.block_login" class="fas fa-skull-crossbones"></em>
        <i v-else class="fas fa-cog"></i>
        {{ toStringName(expert) }}
      </button>
    </td>
    <td>
      <span v-show="isLoading" class="spinner-border spinner-border-sm"></span>
      <p v-show="!isLoading">{{ pipelineCount }}</p>
    </td>
    <td>
      <p v-show="!isLoading">{{ invalidCount }}</p>
    </td>
    <td>
      <p v-show="!isLoading">{{ openCount }}</p>
    </td>
    <td>
      <p v-show="!isLoading">{{ recallCount }}</p>
    </td>
    <td>
      <p v-show="!isLoading">{{ notReachedCount }}</p>
    </td>
    <td>
      <p v-show="!isLoading">{{ noInterestCount }}</p>
    </td>
    <td>
      {{ toStringCallAgents(expert) }}
    </td>
    <td class="text-center">
      <a class="btn btn-link" :href="'/admin/lead/import?expert=' + expert.id">
        <i class="fas fa-cloud-upload-alt"></i>
      </a>
      <button class="btn btn-link" id="btnExportCalendar" v-on:click="onClickCalendarExport">
        <i class="fas fa-calendar"></i>
      </button>
      <b-tooltip target="btnExportCalendar" triggers="click">
        Link kopiert.
      </b-tooltip>
      <button class="btn btn-link" v-on:click="onClickLocationConfig(expert.id)">
        <em class="fas fa-location-arrow"></em>
      </button>
      <button class="btn btn-link" v-on:click="onClickAreaConfig(expert.id)">
        PLZ
      </button>
    </td>
  </tr>
</template>

<script>
export default {
  name: "ExpertLeadStatsTableRowComponent",
  props: {
    expert: {
      type: Object,
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
  data() {
    return {
      pipelineCount: 0,
      invalidCount: 0,
      openCount: 0,
      recallCount: 0,
      notReachedCount: 0,
      noInterestCount: 0,
      isLoading: true
    }
  },
  mounted: function () {
    let route = this.routeStats + '?expertId=' + this.expert.id
    axios.get(route).then((response) => {
      this.pipelineCount = response.data.pipelineCount
      this.invalidCount = response.data.invalidCount
      this.openCount = response.data.openCount
      this.recallCount = response.data.recallCount
      this.notReachedCount = response.data.notReachedCount
      this.noInterestCount = response.data.noInterestCount
    }).finally(() => {
      this.isLoading = false
    });
  },
  methods: {
    onClickLocationConfig(expertId) {
      return window.location.href = this.routeLocationConfig.toString() + '?expertId=' + expertId
    },
    onClickAreaConfig(expertId) {
      return window.location.href = this.routeAreaConfig.toString() + '?expertId=' + expertId
    },
    onClickPipelineConfig(expertId) {
      return window.location.href = this.routePipelineConfig.toString() + '?expert=' + expertId
    },
    onClickCalendarExport() {
      return this.copyStringToClipboard(this.expert.calendar_url);
    },
    toStringCallAgents(expert) {
      return expert.callagents.map((agent) => agent.agent.name).join(', ')
    },
    toStringName(expert) {
      var name = expert.first_name
      if (expert.last_name) {
        name = expert.last_name + ', ' + name
      }
      return name
    }
  }
}
</script>

<style scoped>

</style>