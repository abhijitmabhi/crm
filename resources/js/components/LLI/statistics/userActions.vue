<template>
  <b-card class="lli-card">
    <a class="stretched-link text-decoration-none" :href="getRouteDetailedStats()">
      <div class="mb-3">
        <h3 class="lli-card-title">Nutzeraktionen</h3>
      </div>
      <b-row>
        <b-col class="chartContainer mt-3">
          <b-overlay :show="showLoading" variant="white" opacity="0.8" no-wrap>
          </b-overlay>
          <div v-if="!showLoading && !hasData">
            <i>Keine Daten für den gewählten Zeitraum vorhanden</i>
          </div>
          <canvas ref="chart" :show="!showLoading && hasData"/>
        </b-col>
        <b-col class="mt-3">
          <div class="d-flex justify-content-center align-items-center h-100">
            <statistics-legend-box>
              <div class="d-flex flex-column justify-content-between">
                <statistics-legend-item
                    :gradient-color-start="this.dataColors.website.from"
                    :gradient-color-end="this.dataColors.website.to"
                >Webseitenklicks</statistics-legend-item>
                <statistics-legend-item
                    :gradient-color-start="this.dataColors.route.from"
                    :gradient-color-end="this.dataColors.route.to"
                >Wegbeschreibungen</statistics-legend-item>
                <statistics-legend-item
                    :gradient-color-start="this.dataColors.call.from"
                    :gradient-color-end="this.dataColors.call.to"
                    :is-last="true"
                >Anrufe</statistics-legend-item>
              </div>
            </statistics-legend-box>
          </div>
        </b-col>
      </b-row>
    </a>
  </b-card>
</template>

<script>
import Chart from "chart.js";
import StatisticsLegendBox from "./StatisticsLegendBox";
import StatisticsLegendItem from "./StatisticsLegendItem";

export default {
  components: {StatisticsLegendBox, StatisticsLegendItem},
  data() {
    return {
      showLoading: true,
      statistics: null,
      dataColors: {
        website: {
          from: "#f54646",
          to: "#ff0066",
          gradient: null
        },
        route: {
          from: "#3df5f3",
          to: "#3df575",
          gradient: null
        },
        call: {
          from: "#3da9f5",
          to: "#683df5",
          gradient: null
        },
      },
      chart: null,
      chartAspectRatio: 2,
      widthTest: 0,
      categoryPercentage: 0.2,
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
    websiteClickActionData() {
      return this.getActionData('ACTIONS_WEBSITE');
    },
    callActionData() {
      return this.getActionData('ACTIONS_PHONE');
    },
    routeDirectionActionData() {
      return this.getActionData('ACTIONS_DRIVING_DIRECTIONS');
    },
    dataLabels() {
      return Object.keys(this.statistics);
    },
    chartData() {
      const barPercentage = 1.0;
      const maxBarThickness = 10;
      const datasets = [
        {
          data: this.websiteClickActionData,
          label: "Webseitenklicks",
          backgroundColor: this.dataColors.website.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        },
        {
          data: this.routeDirectionActionData,
          label: "Wegbeschreibungen",
          backgroundColor: this.dataColors.route.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        },
        {
          data: this.callActionData,
          label: "Anrufe",
          backgroundColor: this.dataColors.call.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        },
      ];
      const labels = this.dataLabels;
      return {datasets, labels};
    },
    chartConfig() {
      return {
        type: "BarRounded",
        data: this.chartData,
        options: {
          responsive: true,
          aspectRatio: this.chartAspectRatio,
          maintainAspectRatio: true,
          legend: {
            display: false,
          },
          events: [],
          scales: {
            yAxes: [
              {
                ticks: {
                  fontSize: 14,
                  fontColor: '#afbec6',
                  beginAtZero: true,
                  padding: 15,
                  maxTicksLimit: 10
                },
                gridLines: {
                  drawBorder: false,
                },
              },
            ],
            xAxes: [
              {
                ticks: {
                  fontSize: 14,
                  fontColor: '#afbec6',
                },
                gridLines: {
                  drawBorder: false,
                  display: false,
                },
              },
            ],
          },
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
      const isDetailedView = false;
      const locationInsightType = "ACTION_%";
      const response = await axios.get(
          `/api/companies/${companyId}/locations/${locationId}/statistics/actions`,
          {params: {pastDays, isDetailedView, locationInsightType}}
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
      this.chart.options.aspectRatio = this.chartAspectRatio;
      this.chart.update();
    },
    setChartAspectRatio() {
      let width = window.innerWidth;
      this.widthTest = width;
      this.chartAspectRatio = 3;
      if (width <= 550) {
        this.chartAspectRatio = 1;
      } else if (width <= 700) {
        this.chartAspectRatio = 1.5;
      } else if (width <= 1610) {
        this.chartAspectRatio = 2;
      }
    },
    setCategoryPercentage() {
      let width = window.innerWidth;
      this.categoryPercentage = 0.3;
      if (width <= 500) {
        this.categoryPercentage = 0.9;
      } else if (width <= 600) {
        this.categoryPercentage = 0.8;
      } else if (width <= 1300) {
        this.categoryPercentage = 0.6;
      } else if (width <= 1610) {
        this.categoryPercentage = 0.5;
      }
    },
    destroyChart() {
      this.chart.destroy();
      this.chart = null;
    },
    getActionData(actionDataKey) {
      return Object.values(this.statistics).map(function (stats) {
        return stats[actionDataKey];
      })
    },
    getRouteDetailedStats() {
      return '/'
      // return '/detailedUserActions/' + this.companyId;
    }
  },
  created() {
    this.$nextTick(() => {
      //TODO: for some reason not working
      window.addEventListener('resize', this.setChartAspectRatio);
    });
  },
  mounted() {
    this.setChartAspectRatio();
    this.setCategoryPercentage();
    this.fetchStatistics();
    this.manageChartState();
  },
};
</script>


<style>

.chartContainer {
  flex: 0 0 75%;
  max-width: 75%;
}

@media (max-width: 1610px) {
  .chartContainer {
    flex: 0 0 60%;
    max-width: 60%;
  }
}

@media (max-width: 1140px) {
  .chartContainer {
    flex: none;
    max-width: 100%;
  }
}
</style>
