<template>
    <div class="p-4 d-flex justify-content-center" :class="warningLevel.bg">
        <span class="font-weight-bold fs-24">{{intervalForHumans}} Minuten</span>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            start: 0,
            end: 0
        };
    },
    computed: {
        interval: function() {
            return (this.end - this.start) * 1000;
        },
        intervalForHumans: function() {
            return this.$moment(this.interval).format("mm:ss");
        },
        warningLevel: function() {
            let bg = "";
            let level;
            if (this.interval <= 180000) {
                (bg = "bg-success"), (level = 1);
            }
            if (this.interval > 180000) {
                bg = "bg-warning";
                level = 2;
            }
            if (this.interval > 300000) {
                bg = "bg-danger";
                level = 3;
            }
            if (this.interval > 420000) {
                bg = "bg-danger flashing";
                level = 4;
            }
            return {
                bg: bg,
                level: level
            };
        }
    },
    props: {
        leadId: Number | String
    },
    watch: {
        warningLevel(newVal, oldVal) {
            if (newVal.level !== oldVal.level) {
                this.$emit("levelChanged", newVal);
            }
        }
    },
    methods: {
        startTimer() {
            this.checkCookie();
            this.start = this.getCookieVal();
            this.tick();
        },
        tick() {
            this.end = new this.$moment().unix();
            this.$emit("tick", this.interval);
            setTimeout(() => this.tick(), 1000);
        },
        checkCookie() {
            if (this.$cookies.isKey("intervalStart") == false) {
                this.$cookies.set(
                    "intervalStart",
                    this.leadId + ";" + new this.$moment().unix()
                );
            }
            if (!this.isSameLeadId()) {
                this.$cookies.set(
                    "intervalStart",
                    this.leadId + ";" + new this.$moment().unix()
                );
            }
        },
        isSameLeadId() {
            if (
                this.$cookies.get("intervalStart").split(";")[0] == this.leadId
            ) {
                return true;
            }
            return false;
        },
        getCookieVal() {
            return this.$cookies.get("intervalStart").split(";")[1];
        }
    },
    mounted() {
        this.startTimer();
    }
};
</script>

