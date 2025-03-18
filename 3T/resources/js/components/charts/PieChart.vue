<template>
  <div>
    <canvas ref="pieChart"></canvas>
  </div>
</template>

<script>
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

export default {
  extends: Pie,
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
      labels: ['Under', 'Over', 'Between'],
      datasets: [
        {
          backgroundColor: ['#FF6384', '#FFCE56', '#36A2EB'],
          data: [this.data.under, this.data.over, this.data.between]
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