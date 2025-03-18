<template>
    <div class="chart-container">
      <div class="chart-group">
        <h6 class="text-muted text-center">KPI Pie Charts</h6>
        <div class="d-flex">
          <PieChart v-if="kpiPie" v-for="(data, index) in kpiPie" :key="index" :chart-data="data" />
        </div>
      </div>
  
      <div class="chart-group">
        <h6 class="text-muted text-center">KPI Bar Charts</h6>
        <div class="d-flex">
          <BarChart v-if="kpiBar" v-for="(data, index) in kpiBar" :key="index" :chart-data="data" />
        </div>
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
        kpiPie: null,
        kpiBar: null,
      };
    },
    methods: {
      async fetchKpiChartData() {
        try {
          const response = await axios.get("/chart-data/kpi");
          const data = response.data;
  
          // Pie Charts for each KPI metric
          this.kpiPie = [
            { labels: ["A", "B", "C", "D"], datasets: [{ data: data.kpi.kedisiplinan, backgroundColor: ["green", "yellow", "orange", "red"] }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ data: data.kpi.kesehatan, backgroundColor: ["green", "yellow", "orange", "red"] }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ data: data.kpi.safety, backgroundColor: ["green", "yellow", "orange", "red"] }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ data: data.kpi.r5, backgroundColor: ["green", "yellow", "orange", "red"] }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ data: data.kpi.vt7, backgroundColor: ["green", "yellow", "orange", "red"] }] },
          ];
  
          // Bar Charts for KPI comparison
          this.kpiBar = [
            { labels: ["A", "B", "C", "D"], datasets: [{ label: "Current", data: data.kpi.finalKedisiplinan, backgroundColor: "blue" }, { label: "Final", data: data.kpi.currentKedisiplinan, backgroundColor: "lightblue" }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ label: "Current", data: data.kpi.finalKesehatan, backgroundColor: "blue" }, { label: "Final", data: data.kpi.currentKesehatan, backgroundColor: "lightblue" }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ label: "Current", data: data.kpi.finalSafety, backgroundColor: "blue" }, { label: "Final", data: data.kpi.currentSafety, backgroundColor: "lightblue" }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ label: "Current", data: data.kpi.finalR5, backgroundColor: "blue" }, { label: "Final", data: data.kpi.currentR5, backgroundColor: "lightblue" }] },
            { labels: ["A", "B", "C", "D"], datasets: [{ label: "Current", data: data.kpi.finalVt7, backgroundColor: "blue" }, { label: "Final", data: data.kpi.currentVt7, backgroundColor: "lightblue" }] },
          ];
        } catch (error) {
          console.error("Error fetching KPI chart data:", error);
        }
      },
    },
    mounted() {
      this.fetchKpiChartData();
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
  