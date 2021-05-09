<template>
    <div class="row" style="display:none;">
        <div class="col-md-6">
            <b-card>
                <div class="row text-dark">
                    <div class="align-items-center">
                        <p class="font-weight-bolder fs-22">Statistik {{headerInterval}}</p>
                        <a
                            href
                            class="btn btn-white border mb-4"
                            @click="toggleShowChangeInterval"
                        >Zeitraum ändern</a>
                        <change-interval
                            :start="start"
                            :end="end"
                            v-if="showChangeInterval"
                            @updateDate="updateNumbers"
                        ></change-interval>
                    </div>
                    <div v-if="showStats" class="col text-center">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <p class="font-weight-bold fs-30 mb-0">{{callsNr}}</p>
                                <p class="font-weight-bolder fs-24 mb-0">Anwahlversuche</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <p class="font-weight-bold fs-30 mb-0">{{appointments}}</p>
                                <p class="font-weight-bolder fs-24 mb-0">Termine</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <p class="font-weight-bold fs-30 mb-0">{{averageTime}}</p>
                                <p class="font-weight-bolder fs-24 mb-0">Zeit / Anruf</p>
                            </div>
                            <div class="col-md-6 mb-4">
                                <p class="font-weight-bold fs-30 mb-0">{{successQuota}}%</p>
                                <p class="font-weight-bolder fs-24 mb-0">Erfolgsquote</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="w-100">
                        <p
                            class="font-weight-bold fs-30 mb-4 text-center"
                        >Keine Anrufe im gewählten Zeitraum</p>
                    </div>
                </div>
            </b-card>
            <img v-if="graph" :src="graph" alt="Dein wöchentlicher Erfolg" />
        </div>
    </div>
</template>
<script>
import ChangeInterval from "./ChangeInterval";
export default {
    components: { ChangeInterval },
    data() {
        return {
            interval: "Diese Woche",
            calls: [],
            start: this.$moment()
                .startOf("week")
                .format("YYYY-MM-DD"),
            end: this.$moment()
                .endOf("week")
                .format("YYYY-MM-DD"),
            showChangeInterval: false,
            graph: false,
        };
    },
    computed: {
        showStats() {
            return this.calls.length > 0;
        },
        averageTime() {
            let time, counted_calls, avgTime, duration, s, m, h, d;
            time = 0;
            counted_calls = 0;
            this.calls.forEach(call => {
                if (call.time_spent) {
                    time += call.time_spent;
                    counted_calls += 1;
                }
            });
            if (counted_calls != 0) {
                avgTime = time / counted_calls;
            } else {
                avgTime = 0;
            }
            [s, m, h, d] = this.readableTimes(this.$moment.duration(avgTime));
            if (avgTime < 60000) {
                return s + " Sek";
            } else if (avgTime < 3600000) {
                return `${m}:${s} Min`;
            } else {
                return `${h}:${m} Std`;
            }
        },
        successQuota() {
            return this.calls.length > 0
                ? Math.round((this.appointments * 100) / this.calls.length)
                : 0;
        },
        callsNr() {
            return this.calls.length;
        },
        appointments() {
            return this.calls.filter(call => call.reason === "APPOINTMENT").length;
        },
        headerInterval() {
            if ("diese woche" === this.interval.toLowerCase()) {
                return this.interval;
            }
            return "im Zeitraum von " + this.interval;
        },
    },
    props: {
        agentId: Number,
    },
    methods: {
        readableTimes($duration) {
            try {
                let s, m, h, d;
                s = Math.floor(($duration / 1000) % 60);
                m = Math.floor(($duration / 1000 / 60) % 60);
                h = Math.floor(($duration / (1000 * 60 * 60)) % 24);
                d = Math.floor($duration / (1000 * 60 * 60 * 24));
                return [s, m, h, d];
            } catch (e) {
                console.error(`Readable Times only takes instances of momentjs`);
            }
        },
        findWeek(start, end) {
            var $start = this.$moment(start);
            var $end = this.$moment(end);
            if (
                $start.year() === this.$moment().year() &&
                $start.format("Y-W") === $end.format("Y-W") &&
                $start.isSame($start.startOf("week")) &&
                $end.isSame($end.endOf("week"))
            ) {
                return $start.format("W");
            }
            return false;
        },
        updateNumbers(start, end) {
            axios
                .get(`/api/users/${this.agentId}/comments?start=${start}&end=${end}`)
                .then(response => {
                    this.calls = response.data.filter(comment =>
                        [
                            "APPOINTMENT",
                            "BLACKLIST",
                            "NOT_REACHED",
                            "NO_INTEREST",
                            "OPEN",
                            "RECALL",
                        ].includes(comment.reason)
                    );
                })
                .then(() => {
                    this.updateIntervalString(start, end);
                    this.updateGraph(start, end);
                })
                .catch(error => console.error(error.data));
        },
        updateIntervalString(start, end) {
            const start_week = this.$moment(start).startOf("week");
            const end_week = this.$moment(end).endOf("week");
            if (
                start_week.format("YYYY-MM-DD") === start &&
                end_week.format("YYYY-MM-DD") === end
            ) {
                this.interval = "Diese Woche";
            } else {
                this.interval = `${this.$moment(start).format("DD.MM.YY")} - ${this.$moment(
                    end
                ).format("DD.MM.YY")}`;
            }
        },
        toggleShowChangeInterval(e) {
            e.preventDefault();
            this.showChangeInterval = !this.showChangeInterval;
        },
        updateGraph(start, end) {
            if (this.appointments < 1) {
                this.graph = false;
                return;
            }
            var week = this.findWeek(start, end);
            if (!week) {
                this.graph = false;
                return;
            }
            axios
                .get(`/api/graph?week=${week}&agent_id=${this.agentId}&encoding=data-url`)
                .then(response => (this.graph = response.data));
        },
    },
    mounted() {
        this.updateNumbers(this.start, this.end);
    },
};
</script>
