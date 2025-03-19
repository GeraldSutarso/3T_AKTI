<template>
  <div class="chart-container">
    <!-- Header with buttons -->
    <div class="chart-header">
      <button 
        v-for="tab in tabs"
        :key="tab.id"
        :class="{ active: activeTab === tab.id }"
        @click="switchTab(tab.id)"
      >
        {{ tab.label }}
      </button>
    </div>
    <hr>

    <!-- Body Data Section -->
    <div v-if="activeTab === 'body'" class="chart-content scrollable">
      <div class="chart-group">
        <div class="chart-card">
          <h3>Body Measurements Trend</h3>
          <line-chart 
            :data="bodyData.lineData"
            :options="{ responsive: true }"
          />
        </div>
        
        <div class="chart-card">
          <h3>Body Measurements Distribution</h3>
          <pie-chart 
            :data="bodyData.pieData"
            :options="{ responsive: true }"
          />
        </div>
      </div>
    </div>

    <!-- Physical Section -->
    <div v-if="activeTab === 'physical'" class="chart-content scrollable">
      <div class="chart-group">
        <div class="chart-card">
          <h3>Physical Test Comparison</h3>
          <div class="bar-group">
            <bar-chart 
              v-for="(bar, idx) in physicalData.bars"
              :key="`phy-bar-${idx}`"
              :data="bar"
            />
          </div>
        </div>
        
        <div class="chart-card">
          <h3>Current Physical Distribution</h3>
          <div class="pie-group">
            <pie-chart 
              v-for="(pie, idx) in physicalData.pies"
              :key="`phy-pie-${idx}`"
              :data="pie"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- KPI Section -->
    <div v-if="activeTab === 'kpi'" class="chart-content scrollable">
      <div class="chart-group">
        <div class="chart-card">
          <h3>KPI Current Distribution</h3>
          <div class="pie-group">
            <pie-chart 
              v-for="(pie, idx) in kpiData.pies"
              :key="`kpi-pie-${idx}`"
              :data="pie"
            />
          </div>
        </div>
        
        <div class="chart-card">
          <h3>KPI Final vs Current Comparison</h3>
          <div class="bar-group">
            <bar-chart 
              v-for="(bar, idx) in kpiData.bars"
              :key="`kpi-bar-${idx}`"
              :data="bar"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  data() {
    return {
      activeTab: 'kpi',
      tabs: [
        { id: 'kpi', label: 'KPI' },
        { id: 'body', label: 'Body Data' },
        { id: 'physical', label: 'Physical' }
      ],
      bodyData: {
        lineData: null,
        pieData: null,
        loaded: false
      },
      physicalData: {
        bars: [],
        pies: [],
        loaded: false
      },
      kpiData: {
        pies: [],
        bars: [],
        loaded: false
      }
    }
  },
  methods: {
    async switchTab(tab) {
      this.activeTab = tab
      if (!this[`${tab}Data`].loaded) {
        await this.fetchData(tab)
      }
    },
    
    async fetchData(type) {
      try {
        const response = await axios.get(`/api/charts/${type}`, {
          params: { generation_id: this.currentGenerationId }
        })
        
        this.processData(type, response.data)
        this[`${type}Data`].loaded = true
      } catch (error) {
        console.error(`Error fetching ${type} data:`, error)
      }
    },
    
    processData(type, data) {
      const processors = {
        body: () => {
          this.bodyData = {
            lineData: {
              labels: data.lineDataset.labels,
              datasets: data.lineDataset.datasets
            },
            pieData: {
              labels: data.pieDataset.labels,
              datasets: data.pieDataset.datasets
            }
          }
        },
        physical: () => {
          this.physicalData = {
            bars: data.barCharts.map(bar => ({
              labels: bar.labels,
              datasets: bar.datasets
            })),
            pies: data.pieCharts.map(pie => ({
              labels: pie.labels,
              datasets: pie.datasets
            }))
          }
        },
        kpi: () => {
          this.kpiData = {
            pies: data.pieCharts.map(pie => ({
              labels: pie.labels,
              datasets: pie.datasets
            })),
            bars: data.barCharts.map(bar => ({
              labels: bar.labels,
              datasets: bar.datasets
            }))
          }
        }
      }
      
      processors[type]?.()
    }
  },
  computed: {
    currentGenerationId() {
      // Get from your state management
      return this.$store.state.currentGeneration.id
    }
  }
}
</script>

<style scoped>
.chart-container {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  margin: 20px 0;
}

.chart-header {
  display: flex;
  gap: 15px;
  margin-bottom: 20px;
}

.chart-header button {
  padding: 10px 20px;
  border: 1px solid #ddd;
  border-radius: 4px;
  background: #f8f9fa;
  cursor: pointer;
}

.chart-header button.active {
  background: #2196F3;
  color: white;
  border-color: #2196F3;
}

.scrollable {
  overflow-x: auto;
  padding-bottom: 20px;
}

.chart-group {
  display: flex;
  gap: 30px;
  min-width: max-content;
}

.chart-card {
  background: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin: 10px;
  min-width: 600px;
}

.chart-card h3 {
  margin: 0 0 15px 0;
  color: #2c3e50;
  font-size: 1.1rem;
  font-weight: 600;
}

.pie-group, .bar-group {
  display: flex;
  gap: 20px;
  margin-top: 15px;
}

/* Scrollbar Styling */
.scrollable::-webkit-scrollbar {
  height: 8px;
}

.scrollable::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.scrollable::-webkit-scrollbar-thumb {
  background: #888;
  border-radius: 4px;
}

.scrollable::-webkit-scrollbar-thumb:hover {
  background: #555;
}

/* Loading State */
.loading {
  padding: 40px;
  text-align: center;
  color: #666;
  font-style: italic;
}

/* Responsive Design */
@media (max-width: 768px) {
  .chart-card {
    min-width: 90vw;
    margin: 10px 0;
  }
  
  .chart-header {
    flex-wrap: wrap;
  }
  
  .chart-header button {
    flex: 1;
    min-width: 120px;
    text-align: center;
  }
}
</style>