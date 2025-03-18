<template>
  <div>
    <canvas ref="lineChart"></canvas>
  </div>
</template>

<script>
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

export default {
  extends: Line,
  props: {
    data: {
      type: Object,
      required: true
    }
  },
  mounted() {
    this.renderChart({
      labels: this.data.labels,
      datasets: [
        {
          label: 'Under/Over',
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          data: this.data.underOver
        },
        {
          label: 'Between',
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          data: this.data.between
        }
      ]
    });
  }
};
</script>