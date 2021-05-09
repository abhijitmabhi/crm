<template>
  <b-card class="lli-card lli-table-card">
    <div class="lli-table-card-header d-flex justify-content-between align-items-center">
      <h3 class="lli-card-title pr-5">Wettbewerb</h3>

      <div class="KeywordSelectContainer">
        <b-input-group>
          <b-input-group-prepend class="SelectLabel" v-text="'Keyword:'"/>
          <b-select class="KeywordSelect" v-model="selectedKeywordId" :options="selectOptions"/>
        </b-input-group>
      </div>
    </div>
    <b-card-body class="p-0">
      <b-overlay :show="showLoading" variant="white" opacity="0.8">
        <table class="w-100 mb-4">
          <tbody v-if="!showLoading && keywordObj.ranking.length > 0">
            <tr
                v-for="rank in keywordObj.ranking"
                :key="`${_uid}_${rank.position}`"
                :class="keywordObj.position == rank.position ? 'table-row-highlighted' : ''"
            >
              <td
                  class="h6 lli-table-cell lli-table-cell-left"
              >{{ rank.position }}.
              </td>
              <td
                  class="h6 lli-table-cell lli-table-cell-middle"
                  v-html="decodeUnicode(rank.title)"
              />
              <td class="h6 lli-table-cell lli-table-cell-right">
                <span v-if="rank.positionChange === 0" style="color: #f5b545">
                  {{ rank.positionChange }} <i class="far fa-dot-circle"></i>
                </span>
                <span v-else-if="rank.positionChange < 0" style="color: #f45363">
                  {{ Math.abs(rank.positionChange) }} <i class="far fa-arrow-alt-circle-down"></i>
                </span>
                <span v-else style="color: #2fcc95">
                  {{ rank.positionChange }} <i class="far fa-arrow-alt-circle-up"></i>
                </span>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td class="text-center py-4">
                <i>Keine Daten zur Platzierung der Keywords vorhanden</i>
              </td>
            </tr>
          </tbody>
        </table>
      </b-overlay>
    </b-card-body>
  </b-card>
</template>

<script>
export default {
  data() {
    return {
      showLoading: true,
      statistics: null,
      selectedKeywordId: null,
      keywords: null,
    };
  },
  props: {
    companyId: {
      type: String | Number,
      required: true,
    },
    locationId: {
      type: String | Number,
      required: true,
    },
    pastDays: {
      type: String | Number,
      required: true,
    },
  },
  computed: {
    selectOptions() {
      let keywords = this.keywords ?? [];
      return keywords.map(keyword => {
        return {text: keyword.keyword, value: keyword.id};
      });
    },
    keywordObj() {
      if (this.selectedKeywordId && this.statistics) {
        return this.statistics.find(stat => stat.keywordId === this.selectedKeywordId);
      }
      return null;
    },
  },
  watch: {
    selectOptions() {
      if (this.selectOptions.length)
        this.selectedKeywordId = this.selectOptions[0].value;
      else
        this.selectedKeywordId = null;
    },
    pastDays() {
      this.fetchStatistics();
    },
  },
  methods: {
    async fetchKeywords() {
      const response = await axios.get(
          `/api/companies/${this.companyId}/locations/${this.locationId}/keywords/`
      );
      this.keywords = response.data;
    },
    async fetchStatistics() {
      const {companyId, locationId, pastDays} = this;
      const response = await axios.get(
          `/api/companies/${companyId}/locations/${locationId}/statistics/competition`,
          {params: {pastDays}}
      );
      this.statistics = response.data;
      this.showLoading = false;
    },
    decodeUnicode(str) {
      return JSON.parse(`"${str}"`);
    },
  },
  mounted() {
    if (!this.keywords) {
      this.fetchKeywords();
    }
    this.fetchStatistics();
  },
};
</script>

<style scoped>
.table-row-highlighted {
  opacity: 0.8;
  background-color: #eff2f4;
}

.SelectLabel {
  color: #afbec6;
  margin-left: 20px;
  align-items: center !important;

  font-size: 16px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.44;
  letter-spacing: normal;
  text-align: left;
}

.KeywordSelectContainer {
  width: 50%;
  height: 100%;
  border-radius: 10px;
  border: solid 1px rgba(181, 194, 202, 0.2);
  background-color: #ffffff;
}

.KeywordSelect {
  /*background: none;*/
}

@media (max-width: 595px) {
  .d-flex {
    display: block !important;
  }

  .KeywordSelectContainer {
    width: 100%
  }
}
</style>
