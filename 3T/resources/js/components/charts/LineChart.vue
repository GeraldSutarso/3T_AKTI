<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Line } from "vue-chart-3";

export default {
  components: { Line },
  props: ["chartData"],
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      new Line(this.$refs.chart, {
        type: "line",
        data: this.chartData,
      });
    },
  },
};
</script>
