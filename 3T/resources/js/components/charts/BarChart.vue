<template>
  <div>
    <canvas ref="barChart"></canvas>
  </div>
</template>

<script>
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
  extends: Bar,
  props: {
    data: {
      type: Object,
      required: true
    },
    title: {
      type: String,
      required: false
    }
  },
  mounted() {
    this.renderChart({
      labels: ['A', 'B', 'C', 'D'],
      datasets: [
        {
          label: 'Current',
          backgroundColor: '#36A2EB',
          data: [this.data.current.A, this.data.current.B, this.data.current.C, this.data.current.D]
        },
        {
          label: 'Final',
          backgroundColor: '#FF6384',
          data: [this.data.final.A, this.data.final.B, this.data.final.C, this.data.final.D]
        }
      ]
    }, {
      responsive: true,
      maintainAspectRatio: false,
      title: {
        display: !!this.title,
        text: this.title
      }
    });
  }
};
</script>