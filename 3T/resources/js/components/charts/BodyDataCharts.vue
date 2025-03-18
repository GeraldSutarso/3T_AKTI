<template>
    <div class="chart-container">
      <div class="chart-group">
        <h6 class="text-muted text-center">Body Data Pie Chart</h6>
        <PieChart v-if="bodyDataPie" :chart-data="bodyDataPie" />
      </div>
  
      <div class="chart-group">
        <h6 class="text-muted text-center">Body Data Line Chart</h6>
        <LineChart v-if="bodyDataLine" :chart-data="bodyDataLine" />
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  import { defineAsyncComponent } from "vue";
  
  export default {
    components: {
      PieChart: defineAsyncComponent(() => import("./PieChart.vue")),
      LineChart: defineAsyncComponent(() => import("./LineChart.vue")),
    },
    data() {
      return {
        bodyDataPie: null,
        bodyDataLine: null,
      };
    },
    methods: {
      async fetchBodyData() {
        try {
          const response = await axios.get("/chart-data/bodydata");
          const data = response.data;
  
          // Pie Chart for Body Data Distribution
          this.bodyDataPie = {
            labels: ["Di Bawah Minimum", "Dalam Rentang", "Di Atas Minimum"],
            datasets: [
              {
                data: [data.bodyData.totalUnder, data.bodyData.totalInBetween, data.bodyData.totalOver],
                backgroundColor: ["red", "green", "yellow"],
              },
            ],
          };
  
          // Line Chart for Historical Body Data
          this.bodyDataLine = {
            labels: data.bodyData.periods,
            datasets: [
              {
                label: "Di Bawah Minimum",
                data: data.bodyData.under,
                borderColor: "red",
                backgroundColor: "rgba(255, 0, 0, 0.2)",
                fill: true,
              },
              {
                label: "Dalam Rentang",
                data: data.bodyData.inBetween,
                borderColor: "green",
                backgroundColor: "rgba(0, 255, 0, 0.2)",
                fill: true,
              },
              {
                label: "Di Atas Minimum",
                data: data.bodyData.over,
                borderColor: "yellow",
                backgroundColor: "rgba(255, 255, 0, 0.2)",
                fill: true,
              },
            ],
          };
        } catch (error) {
          console.error("Error fetching body data chart:", error);
        }
      },
    },
    mounted() {
      this.fetchBodyData();
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
  