<template>
    <b-card>
        <div class="citations d-flex flex-column">
            <h3 class="lli-card-title">Citations</h3>
            <b-row class="h-100">
                <b-col md="6" class="d-flex justify-conent-center align-items-center mg">
                    <canvas ref="chart" />
                </b-col>
                <b-col>
                    <legend-box>
                        <div class="citation-score mb-3">
                            <h5>Citation Score</h5>
                            <p>{{ ratio }} von 100</p>
                        </div>
                        <div class="citation-active mb-3 floatright">
                            <h5>Aktive Citations</h5>
                            <p>{{ item.active }}</p>
                        </div>
                        <div class="citation-errors">
                            <h5>NAP Fehler</h5>
                            <b-row>
                                <b-col class="size">Name</b-col>
                                <b-col class="size">{{ item.nap_errors.company_name }}</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="size">Telefon</b-col>
                                <b-col class="size">{{ item.nap_errors.phone }}</b-col>
                            </b-row>
                            <b-row>
                                <b-col class="size">Adresse</b-col>
                                <b-col class="size">{{ item.nap_errors.address }}</b-col>
                            </b-row>
                        </div>
                    </legend-box>
                </b-col>
            </b-row>
        </div>
    </b-card>
</template>

<script>
import legendBox from "./StatisticsLegendBox";

export default {
    components: { legendBox },
    data() {
        return {
            chart: null,
            item: {
                citations: 20,
                active: 10,
                nap_errors: {
                    company_name: 1,
                    phone: 0,
                    address: 2,
                },
            },
        };
    },
    computed: {
        ratio() {
            return (this.item.active * 100) / this.item.citations;
        },
    },
    methods: {
        getConfig(gradient) {
            return {
                type: "radialGauge",
                data: {
                    datasets: [
                        {
                            data: [this.ratio],
                            backgroundColor: [gradient, "#f0f1f5"],
                            borderwidth: 0,
                        },
                    ],
                    labels: ["Citations"],
                },
                label: "Citations",
                options: {
                    events: [],
                    legend: {
                        display: false,
                    },
                    centerArea: {
                        text(value, options) {
                            return `${value}%\n${options.textMin} von ${options.textMax}`;
                        },
                        textMin: this.item.active,
                        textMax: this.item.citations,
                    },
                },
            };
        },
    },
    mounted() {
        const ctx = this.$refs.chart.getContext("2d");
        const gradientStroke = ctx.createLinearGradient(500, 0, 500, 150);
        gradientStroke.addColorStop(0, "#4af5f2");
        gradientStroke.addColorStop(1, "#49f284");
        this.chart = new Chart(ctx, this.getConfig(gradientStroke));
    },
};
</script>

<style>

.size{
  font-size:16px;
}

@media (min-width:576px) and (max-width:760px){
  .floatright{
    float:right;
    margin-top:-68px;
  }
}

@media (min-width:780px) and (max-width:1200px){
  .floatright{
    float:right;
    margin-top:-68px;
  }
}

@media (max-width:767px){
  .mg{
    margin-top:30px;
    margin-bottom:30px;
  }
}
</style>
