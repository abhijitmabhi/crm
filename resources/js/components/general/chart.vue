<template>
    <div class="chart">
        <canvas ref="canvas" />
    </div>
</template>

<script>
import Chart from "chart.js";
export default {
    props: {
        config: {
            required: true,
            type: Object,
        },
    },
    mounted() {
        const ctx = this.$refs.canvas.getContext("2d");
        this.chart = new Chart(ctx, this.config);
    },
};
</script>
