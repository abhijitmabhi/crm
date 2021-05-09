import Chart from "chart.js";
import "chartjs-chart-radial-gauge";
import { merge } from "lodash";

Chart.controllers.radialGauge.prototype.drawCenterText = function({ options, value }) {
    let fontSize = options.fontSize || `${(this.innerRadius / 50).toFixed(2)}em`;
    if (typeof fontSize === "number") {
        fontSize = `${fontSize}px`;
    }

    const fontFamily = options.fontFamily || Chart.defaults.global.defaultFontFamily;
    const color = options.fontColor || Chart.defaults.global.defaultFontColor;

    let text = typeof options.text === "function" ? options.text(value, options) : options.text;
    text = text || `${value}`;
    const parts = text.split("\n");
    this.chart.ctx.font = `${fontSize} ${fontFamily}`;
    this.chart.ctx.fillStyle = color;
    this.chart.ctx.textBaseline = "middle";
    const maxWidth = 2 * this.innerRadius * 0.8;
    if (1 < parts.length) {
        // TODO: incorporate Y-Axis checks
        if (parts.every(part => this.chart.ctx.measureText(part).width < maxWidth)) {
            const fs = (this.innerRadius / 40).toFixed(2);
            const bodyFs = parseInt(
                getComputedStyle(document.getElementsByTagName("body")[0]).fontSize,
                10
            );
            this.chart.ctx.font = `700 ${fs}em ${fontFamily}`;
            const textHeight = fs * bodyFs;
            const sumHeight = parts.length * textHeight;
            this.chart.ctx.translate(0, -Math.round(sumHeight / 2) + Math.round(textHeight / 2));
            let textY = 0;
            parts.forEach(part => {
                const { width } = this.chart.ctx.measureText(part);
                const textX = Math.round(-width / 2);
                this.chart.ctx.fillText(part, textX, textY);
                this.chart.ctx.font = `${fontSize} ${fontFamily}`;
                textY += textHeight;
            });
        }
    } else {
        const textWidth = this.chart.ctx.measureText(text).width;
        const textX = Math.round(-textWidth / 2);

        // only display the text if it fits
        if (textWidth < maxWidth) {
            this.chart.ctx.fillText(text, textX, 0);
        }
    }
};

// Bar chart with rounded corners

Chart.defaults.BarRounded = merge(Chart.defaults.bar, {
    radius: 5,
});

Chart.defaults.global.datasets.BarRounded = {
    categoryPercentage: 0.8,
    barPercentage: 0.9,
};

Chart.controllers.BarRounded = Chart.controllers.bar.extend({
    draw(ease) {
        let chart = this.chart;
        let ctx = chart.ctx;
        let scale = this._getValueScale();
        let rects = this.getMeta().data;
        let datasets = this.getDataset();
        let ilen = rects.length;
        var i = 0;

        let chartBottom = chart.chartArea.bottom;

        for (; i < ilen; ++i) {
            let val = scale._parseValue(datasets.data[i]);
            if (!isNaN(val.min) && !isNaN(val.max)) {
                const model = rects[i]._model;
                let posX = model.x - (model.width / 2);
                let posY = model.y;

                ctx.beginPath();
                Chart.helpers.canvas.roundedRect(
                    ctx,
                    posX,
                    // using chart.config.options.radius might be not necessary, maybe just hardcoded 20px would do the same trick
                    chart.config.options.radius * 2,
                    model.width,
                    model.base - chart.config.options.radius * 2,
                    chart.config.options.radius
                );
                ctx.fillStyle = '#eff2f4';
                ctx.fill();

                ctx.beginPath();
                let barHeight = chartBottom - posY;
                Chart.helpers.canvas.roundedRect(
                    ctx,
                    posX,
                    model.y + barHeight * (1 - ease),
                    model.width,
                    barHeight * ease,
                    chart.config.options.radius
                );
                ctx.fillStyle = model.backgroundColor;
                ctx.fill();
            }
        }
    },
});
