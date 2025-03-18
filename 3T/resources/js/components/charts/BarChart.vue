<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Bar } from "vue-chart-3";

export default {
  components: { Bar },
  props: ["chartData"],
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      new Bar(this.$refs.chart, {
        type: "bar",
        data: this.chartData,
      });
    },
  },
};
</script>
