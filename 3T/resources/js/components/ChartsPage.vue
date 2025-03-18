<template>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Performance Metrics</h1>
      
      <div v-if="loading" class="flex justify-center items-center h-64">
        <div class="spinner"></div>
      </div>
      <div v-else>
        <div class="mb-4">
          <label for="generation" class="block text-sm font-medium text-gray-700">Select Generation:</label>
          <select 
            id="generation" 
            v-model="selectedGeneration" 
            @change="loadChartData"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
            <option v-for="gen in generations" :key="gen.id" :value="gen.id">{{ gen.name }}</option>
          </select>
        </div>
        
        <ChartComponent :generationId="selectedGeneration" />
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted } from 'vue';
  import ChartComponent from './ChartComponent.vue';
  
  export default {
    name: 'ChartsPage',
    components: {
      ChartComponent
    },
    setup() {
      const loading = ref(true);
      const generations = ref([]);
      const selectedGeneration = ref(null);
      
      const fetchGenerations = async () => {
        try {
          const response = await fetch('/api/generations');
          const data = await response.json();
          generations.value = data;
          
          if (data.length > 0) {
            selectedGeneration.value = data[0].id;
          }
          
          loading.value = false;
        } catch (error) {
          console.error('Error fetching generations:', error);
          loading.value = false;
        }
      };
      
      const loadChartData = () => {
        // This function exists to trigger a refresh when the generation changes
        // The actual data loading happens in the ChartComponent
      };
      
      onMounted(() => {
        fetchGenerations();
      });
      
      return {
        loading,
        generations,
        selectedGeneration,
        loadChartData
      };
    }
  };
  </script>
  
  <style scoped>
  .spinner {
    border: 4px solid rgba(0, 0, 0, 0.1);
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border-left-color: #09f;
    animation: spin 1s ease infinite;
  }
  
  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }
    100% {
      transform: rotate(360deg);
    }
  }
  </style>