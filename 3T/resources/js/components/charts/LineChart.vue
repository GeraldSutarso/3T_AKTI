<template>
  <div class="chart-wrapper">
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Line } from 'vue-chartjs'

export default {
  extends: Line,
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
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        },
        ...this.options
      }
    )
  }
}
</script>

<style scoped>
.chart-wrapper {
  position: relative;
  min-width: 600px;
  height: 400px;
}
</style>