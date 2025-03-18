<template>
  <div class="chart-container">
    <div class="chart-buttons">
      <button @click="showBodyData">Body Data</button>
      <button @click="showPhysical">Physical</button>
      <button @click="showKpi">KPI</button>
    </div>
    <hr />
    <div class="chart-box" v-if="currentChart === 'bodyData'">
      <!-- Line Chart for Body Data -->
      <line-chart :data="bodyData.lineChart"></line-chart>
      <!-- Pie Chart for Body Data -->
      <pie-chart :data="bodyData.pieChart"></pie-chart>
    </div>
    <div class="chart-box" v-if="currentChart === 'physical'">
      <!-- Bar Charts for Physical Data -->
      <bar-chart :data="physicalData.barCharts.finalAvgAboveKKM"></bar-chart>
      <bar-chart :data="physicalData.barCharts.currentAvgAboveKKM"></bar-chart>
      <!-- Pie Charts for Physical Data -->
      <pie-chart :data="physicalData.pieCharts.avgBelowKKM"></pie-chart>
      <pie-chart :data="physicalData.pieCharts.avgAboveKKM"></pie-chart>
    </div>
    <div class="chart-box" v-if="currentChart === 'kpi'">
      <!-- Pie Charts for KPI Data -->
      <pie-chart v-for="(data, index) in kpiData.pieCharts" :key="index" :data="data"></pie-chart>
      <!-- Bar Charts for KPI Data -->
      <bar-chart v-for="(data, index) in kpiData.barCharts" :key="index" :data="data"></bar-chart>
    </div>
  </div>
</template>

<script>
import LineChart from './LineChart.vue';
import PieChart from './PieChart.vue';
import BarChart from './BarChart.vue';

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
    fetchBodyData() {
      // Fetch body data from the server
      axios.get('/api/charts/body-data').then(response => {
        this.bodyData = response.data;
      });
    },
    fetchPhysicalData() {
      // Fetch physical data from the server
      axios.get('/api/charts/physical').then(response => {
        this.physicalData = response.data;
      });
    },
    fetchKpiData() {
      // Fetch KPI data from the server
      axios.get('/api/charts/kpi').then(response => {
        this.kpiData = response.data;
      });
    }
  },
  mounted() {
    this.fetchBodyData();
  }
};
</script>

<style>
.chart-container {
  width: 100%;
  overflow-x: scroll;
}
.chart-buttons {
  display: flex;
  justify-content: space-around;
}
.chart-box {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>