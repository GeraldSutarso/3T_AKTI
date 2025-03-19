<template>
  <div class="chart-wrapper">
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Pie } from 'vue-chartjs'

export default {
  extends: Pie,
  props: {
    data: Object,
    options: Object
  },
  mounted() {
    this.renderChart(
      {
        labels: this.data.labels,
        datasets: this.data.datasets
      },
      {
        responsive: true,
        maintainAspectRatio: false,
        ...this.options
      }
    )
  }
}
</script>

<style scoped>
.chart-wrapper {
  position: relative;
  min-width: 300px;
  height: 300px;
}
</style>