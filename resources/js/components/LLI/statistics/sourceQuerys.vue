<template>
  <b-card class="lli-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="lli-card-title">Quelle der Suchanfragen</h3>
    </div>
    <b-row class="d-flex justify-content-center align-items-center">
      <b-col md="6" class="align-items-center mt-3">
        <b-overlay :show="showLoading" variant="white" opacity="0.8" no-wrap>
        </b-overlay>
        <div v-if="!showLoading && !hasData">
          <i>Keine Daten für den gewählten Zeitraum vorhanden</i>
        </div>
        <canvas class="ChartCanvas" ref="chart" :show="!showLoading && hasData"/>
      </b-col>
      <b-col class="mt-3">
        <statistics-legend-box>
          <div>
            <h4 class="LegendTitle">{{ queries_total }}</h4>
            <p class="LegendSubtitle">Suchanfragen</p>
            <div class="d-flex flex-column justify-content-between">
              <statistics-legend-item
                  :gradient-color-start="this.dataColors.direct.from"
                  :gradient-color-end="this.dataColors.direct.to"
              >
                {{ queries_direct }} Direkt
              </statistics-legend-item>
              <statistics-legend-item
                  :gradient-color-start="this.dataColors.indirect.from"
                  :gradient-color-end="this.dataColors.indirect.to"
              >
                {{ queries_indirect }} Indirekt
              </statistics-legend-item>
              <statistics-legend-item
                  :gradient-color-start="this.dataColors.chain.from"
                  :gradient-color-end="this.dataColors.chain.to"
                  :is-last="true"
              >
                {{ queries_chain }} Markenbezogen
              </statistics-legend-item>
            </div>
          </div>
        </statistics-legend-box>
      </b-col>
    </b-row>
  </b-card>
</template>

<script>
import Chart from "chart.js";
import StatisticsLegendItem from "./StatisticsLegendItem";
import StatisticsLegendBox from "./StatisticsLegendBox";


Chart.pluginService.register({
  afterUpdate: function (chart) {
    var a = chart.config.data.datasets.length - 1;
    for (let i in chart.config.data.datasets) {
      for (var j = chart.config.data.datasets[i].data.length - 1; j >= 0; --j) {
        if (Number(j) === (chart.config.data.datasets[i].data.length - 1))
          continue;
        var arc = chart.getDatasetMeta(i).data[j];
        arc.round = {
          x: (chart.chartArea.left + chart.chartArea.right) / 2,
          y: (chart.chartArea.top + chart.chartArea.bottom) / 2,
          radius: chart.innerRadius + chart.radiusLength / 2 + (a * chart.radiusLength),
          thickness: chart.radiusLength / 2 - 1,
          backgroundColor: arc._model.backgroundColor
        }
      }
      a--;
    }
  },

  afterDraw: function (chart) {
    var ctx = chart.chart.ctx;
    for (let i in chart.config.data.datasets) {
      for (var j = chart.config.data.datasets[i].data.length - 1; j >= 0; --j) {
        if (Number(j) === (chart.config.data.datasets[i].data.length - 1))
          continue;
        var arc = chart.getDatasetMeta(i).data[j];
        var startAngle = Math.PI / 2 - arc._view.startAngle;
        var endAngle = Math.PI / 2 - arc._view.endAngle;

        ctx.save();
        ctx.translate(arc.round.x, arc.round.y);
        console.log(arc.round.startAngle)
        ctx.fillStyle = arc.round.backgroundColor;
        ctx.beginPath();
        ctx.arc(arc.round.radius * Math.sin(startAngle), arc.round.radius * Math.cos(startAngle), arc.round.thickness, 0, 2 * Math.PI);
        ctx.arc(arc.round.radius * Math.sin(endAngle), arc.round.radius * Math.cos(endAngle), arc.round.thickness, 0, 2 * Math.PI);
        ctx.closePath();
        ctx.fill();
        ctx.restore();
      }
    }
  },
});

export default {
  components: {StatisticsLegendBox, StatisticsLegendItem},
  data() {
    return {
      showLoading: true,
      statistics: null,
      dataColors: {
        direct: {
          from: "#f54646",
          to: "#ff0066",
          gradient: null
        },
        indirect: {
          from: "#3df5f3",
          to: "#3df575",
          gradient: null
        },
        chain: {
          from: "#3da9f5",
          to: "#683df5",
          gradient: null
        },
      },
      backgroundColor: "#f0f1f5",
      chart: null,
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
    hasData() {
      return this.statistics != null;
    },
    queries_total() {
      if (this.hasData) {
        return Object.values(this.statistics).reduce((v1, v2) => v1 + v2, 0);
      }
      return 0;
    },
    queries_direct() {
      if (this.hasData) {
        return this.statistics['QUERIES_DIRECT'];
      }
      return 0;
    },
    queries_indirect() {
      if (this.hasData) {
        return this.statistics['QUERIES_INDIRECT'];
      }
      return 0;
    },
    queries_chain() {
      if (this.hasData) {
        return this.statistics['QUERIES_CHAIN'];
      }
      return 0;
    },
    chartData() {
      const datasets = [
        {
          data: [this.queries_direct, this.queries_total - this.queries_direct],
          backgroundColor: [this.dataColors.direct.gradient, this.backgroundColor],
        },
        {
          data: [this.queries_indirect, this.queries_total - this.queries_indirect],
          backgroundColor: [this.dataColors.indirect.gradient, this.backgroundColor],
        },
        {
          data: [this.queries_chain, this.queries_total - this.queries_chain],
          backgroundColor: [this.dataColors.chain.gradient, this.backgroundColor],
        },
      ];
      // not displayed but needed for rerendering despite chartjs legend being disabled
      const labels = ["Direkt", "Indirekt", "Markenbezogen"];
      return {datasets, labels};
    },
    chartConfig() {
      return {
        type: "doughnut",
        data: this.chartData,
        options: {
          aspectRatio: 1,
          maintainAspectRatio: true,
          responsive: true,
          legend: {
            display: false,
          },
          scales: {
            display: false,
          },
          events: [],
        },
      };
    },
  },
  watch: {
    statistics() {
      this.manageChartState();
    },
    pastDays() {
      this.fetchStatistics();
    },
    locationId() {
      this.fetchStatistics();
    }
  },
  methods: {
    async fetchStatistics() {
      const {companyId, locationId, pastDays} = this;
      const response = await axios.get(
          `/api/companies/${companyId}/locations/${locationId}/statistics/queries`,
          {params: {pastDays}}
      );
      this.statistics = response.data;
      this.showLoading = false;
    },
    manageChartState() {
      if (this.hasData) {
        if (this.chart) {
          this.rerenderChart();
        } else {
          this.renderChart();
        }
      } else {
        if (this.chart) {
          this.destroyChart();
        }
      }
    },
    renderChart() {
      const ctx = this.$refs.chart.getContext("2d");
      Object.values(this.dataColors).forEach((color) => color.gradient = this.getGradient(color.from, color.to))
      this.chart = new Chart(ctx, this.chartConfig);
    },
    getGradient(fromColor, toColor) {
      const ctx = this.$refs.chart.getContext("2d");
      const gradient = ctx.createLinearGradient(0, 300, 0, 0);
      gradient.addColorStop(0, fromColor);
      gradient.addColorStop(1, toColor);
      return gradient;
    },
    rerenderChart() {
      this.chart.data = this.chartData;
      this.chart.update();
    },
    destroyChart() {
      this.chart.destroy();
      this.chart = null;
    },
  },
  mounted() {
    this.fetchStatistics();
    this.manageChartState();
  },
};
</script>

<style>

.ChartCanvas {
  max-height: 300px;
  max-width: 300px;
  margin: 0 auto;
}

.LegendTitle {
  font-size: 24px;
  font-weight: bold;
  color: #34373a;
  margin-bottom: 0;
}

.LegendSubtitle {
  font-size: 18px;
  color: #34373a;
  margin-bottom: 35px;
}

</style>