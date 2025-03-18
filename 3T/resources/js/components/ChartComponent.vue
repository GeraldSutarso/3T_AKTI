<template>
  <div class="chart-container">
    <!-- Chart Navigation Buttons -->
    <div class="chart-buttons">
      <button :class="{ active: currentChart === 'bodyData' }" @click="showBodyData">Body Data</button>
      <button :class="{ active: currentChart === 'physical' }" @click="showPhysical">Physical</button>
      <button :class="{ active: currentChart === 'kpi' }" @click="showKpi">KPI</button>
    </div>
    <hr />

    <!-- Body Data Charts -->
    <div class="chart-box" v-if="currentChart === 'bodyData'">
      <line-chart :data="bodyData.lineChart"></line-chart>
      <pie-chart :data="bodyData.pieChart"></pie-chart>
    </div>

    <!-- Physical Charts -->
    <div class="chart-box" v-if="currentChart === 'physical'">
      <bar-chart :data="physicalData.barCharts.finalAvgAboveKKM"></bar-chart>
      <bar-chart :data="physicalData.barCharts.currentAvgAboveKKM"></bar-chart>
      <pie-chart :data="physicalData.pieCharts.avgBelowKKM"></pie-chart>
      <pie-chart :data="physicalData.pieCharts.avgAboveKKM"></pie-chart>
    </div>

    <!-- KPI Charts -->
    <div class="chart-box" v-if="currentChart === 'kpi'">
      <pie-chart v-for="(data, index) in kpiData.pieCharts" :key="index" :data="data"></pie-chart>
      <bar-chart v-for="(data, index) in kpiData.barCharts" :key="index" :data="data"></bar-chart>
    </div>
  </div>
</template>

<script>
import LineChart from './charts/LineChart.vue';
import PieChart from './charts/PieChart.vue';
import BarChart from './charts/BarChart.vue';
import axios from 'axios';

export default {
  components: {
    LineChart,
    PieChart,
    BarChart
  },
  data() {
    return {
      currentChart: 'bodyData',
      bodyData: {},
      physicalData: {},
      kpiData: {}
    };
  },
  methods: {
    showBodyData() {
      this.currentChart = 'bodyData';
      this.fetchBodyData();
    },
    showPhysical() {
      this.currentChart = 'physical';
      this.fetchPhysicalData();
    },
    showKpi() {
      this.currentChart = 'kpi';
      this.fetchKpiData();
    },
    async fetchBodyData() {
      try {
        const response = await axios.get('/api/charts/body-data');
        this.bodyData = response.data;
      } catch (error) {
        console.error("Error fetching Body Data:", error);
      }
    },
    async fetchPhysicalData() {
      try {
        const response = await axios.get('/api/charts/physical');
        this.physicalData = response.data;
      } catch (error) {
        console.error("Error fetching Physical Data:", error);
      }
    },
    async fetchKpiData() {
      try {
        const response = await axios.get('/api/charts/kpi');
        this.kpiData = response.data;
      } catch (error) {
        console.error("Error fetching KPI Data:", error);
      }
    }
  },
  mounted() {
    this.fetchBodyData();
  }
};
</script>

<style scoped>
/* Chart Container */
.chart-container {
  width: 100%;
  padding: 15px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Chart Buttons */
.chart-buttons {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 10px;
}

.chart-buttons button {
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  background: #007bff;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
}

.chart-buttons button:hover {
  background: #0056b3;
}

.chart-buttons button.active {
  background: #28a745;
}

/* Chart Box */
.chart-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
}
</style>
