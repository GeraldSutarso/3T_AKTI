<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Pie } from "vue-chart-3";

export default {
  components: { Pie },
  props: ["chartData"],
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      new Pie(this.$refs.chart, {
        type: "pie",
        data: this.chartData,
      });
    },
  },
};
</script>
