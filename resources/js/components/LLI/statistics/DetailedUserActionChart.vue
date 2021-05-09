<template>
  <b-card
      class="lli-card mt-3"
  >
    <h3 class="lli-card-title">{{ title }}</h3>

    <b-row>
      <b-col class="chartContainer mt-3">
        <b-overlay :show="showLoading" variant="white" opacity="0.8" no-wrap>
        </b-overlay>
        <div v-if="!showLoading && !hasData">
          <i>Keine Daten für den gewählten Zeitraum vorhanden</i>
        </div>
        <!--<canvas ref="chart" :show="!showLoading && hasData"/>-->
        <canvas id="chart" ref="chart"/>
      </b-col>
    </b-row>

  </b-card>
</template>


<script>
import Chart from "chart.js";
// import ChartJsPluginDataLabels from 'chartjs-plugin-datalabels';

export default {
  name: "DetailedUserActionChart",
  props: {
    title: {
      type: String,
      required: true
    },
    companyId: {
      type: Number,
      required: true
    },
    locationId: {
      type: Number,
      required: true
    },
    pastDays: {
      type: Number,
      required: true
    },
  },
  data() {
    return {
      showLoading: true,
      statistics: null,
      chart: null,
      chartAspectRatio: 2,
      widthTest: 0,
      categoryPercentage: 0.2,
      locationInsightType: this.getLocationInsightType(),
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

    }
  },
  mounted: function () {
    this.setChartAspectRatio();
    this.setCategoryPercentage();
    this.fetchStatistics();
    this.manageChartState();
    this.renderChart();

  },
  methods: {
    //sets chartAspectRatio dependent on the the viewport width (changes size of the chart)
    setChartAspectRatio() {
      let width = window.innerWidth; // This will return the width of the viewport
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
    //dont know what the setcategory percentage variable is supposed to change
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
    //getting data -> needs change, need non aggregated values as well
    async fetchStatistics() {
      const {companyId, locationId, pastDays, locationInsightType} = this;
      const isDetailedView = true;
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
      //sets gradient for each table in dataColors (is null when mounted)
      Object.values(this.dataColors).forEach((color) => color.gradient = this.getGradient(color.from, color.to))
      this.chart = new Chart(ctx, this.chartConfig);

    },
    rerenderChart() {
      //is called when the selected timeframe changes by the user (see watch)
      this.chart.data = this.chartData;
      this.chart.options.aspectRatio = this.chartAspectRatio;
      this.chart.update();
    },
    destroyChart() {
      //is called if the backend doesnt provide us with any data for some reason
      this.chart.destroy();
      this.chart = null;
    },
    getGradient(fromColor, toColor) {
      const ctx = this.$refs.chart.getContext("2d");
      const gradient = ctx.createLinearGradient(0, 300, 0, 0);
      gradient.addColorStop(0, fromColor);
      gradient.addColorStop(1, toColor);
      return gradient;
    },
    getActionData(actionDataKey) {
      return Object.values(this.statistics).map(function (stats) {
        return stats[actionDataKey];
      })
    },
    getLocationInsightType() {
      if(this.title === "Webseitenklicks") {
        return "ACTIONS_WEBSITE";
      } else if (this.title === "Wegbeschreibungen") {
        return "ACTIONS_DRIVING_DIRECTIONS";
      } else {
        return "ACTIONS_PHONE";
      }
    }
  },
  computed: {
    getDataSets() {
      const barPercentage = 1.0;
      const maxBarThickness = 30;
      if(this.title === "Webseitenklicks") {
        return [{
          data: this.websiteClickActionData,
          backgroundColor: this.dataColors.website.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        }];
      } else if (this.title === "Wegbeschreibungen") {
        return [{
          data: this.routeDirectionActionData,
          backgroundColor: this.dataColors.route.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        }];
      } else {
        return [{
          data: this.callActionData,
          backgroundColor: this.dataColors.call.gradient,
          categoryPercentage: this.categoryPercentage,
          barPercentage: barPercentage,
          maxBarThickness: maxBarThickness,
        }];
      }
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
    getDataLabels() {
      return Object.keys(this.statistics);
    },
    hasData() {
      return this.statistics != null;
    },
    chartConfig() {
      return {
        type: "bar",
        data: this.chartData,
        options: {
          responsive: true,
          aspectRatio: this.chartAspectRatio,
          maintainAspectRatio: true,
          layout: {
            padding: {
              top: 40
            }
          },
          plugins: {
            datalabels: {
                  align: 'end',
                  anchor: 'end',
                  font: {size: 18},
                  offset: 8
            }
          },
          legend: {
            display: false,
          },
          events: [],
          scales: {
            yAxes: [
              {
                ticks: {
                  display: false
                },
                gridLines: {
                  drawBorder: false,
                  display: false
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
    chartData() {
      const barPercentage = 1.0;
      const maxBarThickness = 10;
      const datasets = this.getDataSets;
      const labels = this.getDataLabels;
      return {datasets, labels};
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
}

</script>

<style scoped>
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