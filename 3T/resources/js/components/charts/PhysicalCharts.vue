<template>
    <div class="chart-container">
      <div class="chart-group">
        <h6 class="text-muted text-center">Physical Performance Bar Chart</h6>
        <BarChart v-if="physicalBarChart" :chart-data="physicalBarChart" />
      </div>
  
      <div class="chart-group">
        <h6 class="text-muted text-center">Bleep Test Pie Chart</h6>
        <PieChart v-if="physicalPieChart1" :chart-data="physicalPieChart1" />
      </div>
  
      <div class="chart-group">
        <h6 class="text-muted text-center">Average Test Pie Chart</h6>
        <PieChart v-if="physicalPieChart2" :chart-data="physicalPieChart2" />
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import { defineAsyncComponent } from "vue";
  
  export default {
    components: {
      PieChart: defineAsyncComponent(() => import("./PieChart.vue")),
      BarChart: defineAsyncComponent(() => import("./BarChart.vue")),
    },
    data() {
      return {
        physicalBarChart: null,
        physicalPieChart1: null,
        physicalPieChart2: null,
      };
    },
    methods: {
      async fetchPhysicalData() {
        try {
          const response = await axios.get("/chart-data/physical");
          const data = response.data;
  
          // Bar Chart for Physical Data Comparison
          this.physicalBarChart = {
            labels: data.physical.periods,
            datasets: [
              {
                label: "Rata-rata ≥ avg_kkm",
                data: data.physical.avgAbove,
                backgroundColor: "blue",
              },
              {
                label: "Bleep ≥ bleep_kkm",
                data: data.physical.bleepAbove,
                backgroundColor: "purple",
              },
            ],
          };
  
          // Pie Chart for Bleep Test
          this.physicalPieChart1 = {
            labels: ["Bleep < bleep_kkm", "Bleep ≥ bleep_kkm"],
            datasets: [
              {
                data: [data.physical.currentBleepBelow, data.physical.currentBleepAbove],
                backgroundColor: ["red", "blue"],
              },
            ],
          };
  
          // Pie Chart for Average Test
          this.physicalPieChart2 = {
            labels: ["Rata-rata < avg_kkm", "Rata-rata ≥ avg_kkm"],
            datasets: [
              {
                data: [data.physical.currentAvgBelow, data.physical.currentAvgAbove],
                backgroundColor: ["red", "green"],
              },
            ],
          };
        } catch (error) {
          console.error("Error fetching physical data chart:", error);
        }
      },
    },
    mounted() {
      this.fetchPhysicalData();
    },
  };
  </script>
  
  <style scoped>
  .chart-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
    overflow-x: auto;
  }
  
  .chart-group {
    border: 1px solid #ddd;
    padding: 15px;
    background: #fff;
    border-radius: 8px;
  }
  </style>
  