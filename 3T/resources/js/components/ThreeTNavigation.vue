<template>
  <div class="three-t-widget p-3 border rounded bg-white">
    <!-- Category Switcher -->
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="fw-bold">3T Forms</h5>
    </div>

    <div class="btn-group w-100 mb-3">
      <button class="btn btn-primary btn-sm" @click="activeCategory = 'FinalKPI'">KPI</button>
      <button class="btn btn-success btn-sm" @click="activeCategory = 'FinalBodyData'">Data BB-TB</button>
      <button class="btn btn-warning btn-sm" @click="activeCategory = 'FinalPhysical'">Data Tes Fisik</button>
    </div>

    <hr>

    <!-- Current 3T Form -->
    <h6 class="text-primary">{{ currentFormTitle }}</h6>
    <div class="table-responsive">
      <table class="table table-hover table-sm">
        <tbody>
          <!-- If no form exists, show a message but allow clicking -->
          <tr v-if="currentForms.length === 0">
            <td class="text-muted text-center clickable-row" @click="navigateToForm(null)">
              Tidak ada data, klik untuk menambahkan
            </td>
          </tr>
          <tr 
            v-for="form in currentForms" 
            :key="form.id" 
            @click="navigateToForm(form)" 
            class="clickable-row">
            <td>{{ form.name }} ({{ form.period }})</td>
          </tr>
        </tbody>
      </table>
    </div>

    <hr>

    <!-- Final 3T Forms -->
    <h6 class="text-secondary">{{ finalCategoryTitle }}</h6>
    <ul class="list-group">
      <li v-if="filteredDocuments.length === 0" class="list-group-item text-muted text-center">None</li>
      <li 
        class="list-group-item clickable-row" 
        v-for="doc in filteredDocuments" 
        :key="doc.id" 
        @click="navigateToForm(doc)">
        <a href="#" class="text-decoration-none">{{ doc.name }} ({{ doc.period }})</a>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      activeCategory: "FinalKPI",
      finalDocuments: {
        FinalKPI: [],
        FinalBodyData: [],
        FinalPhysical: []
      },
      currentForms: []
    };
  },
  computed: {
    filteredDocuments() {
      return this.finalDocuments[this.activeCategory] || [];
    },
    currentFormTitle() {
      return {
        FinalKPI: "Dokumen KPI Saat Ini",
        FinalBodyData: "Dokumen Data BB-TB Saat Ini",
        FinalPhysical: "Dokumen Tes Fisik Saat Ini"
      }[this.activeCategory];
    },
    finalCategoryTitle() {
      return {
        FinalKPI: "Riwayat KPI",
        FinalBodyData: "Riwayat BB-TB",
        FinalPhysical: "Riwayat Tes Fisik"
      }[this.activeCategory];
    }
  },
  methods: {
    async fetchDashboardData() {
      try {
        const response = await axios.get("/dashboard-data");
        this.finalDocuments.FinalKPI = response.data.finalKpis;
        this.finalDocuments.FinalBodyData = response.data.finalBodyData;
        this.finalDocuments.FinalPhysical = response.data.finalPhysicals;
        this.currentForms = [
          response.data.currentKpi,
          response.data.currentPhysical,
          response.data.currentBodyData
        ].filter(Boolean);
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    navigateToForm(form) {
      if (this.activeCategory === "FinalKPI" || !form) {
        window.location.href = "/form/kpi"; // Use Laravel route
      } else {
        window.location.href = `/form/${form.id}`; // Navigate to other forms
      }
    }
  },
  mounted() {
    this.fetchDashboardData();
  }
};
</script>


<style scoped>
.clickable-row {
  cursor: pointer;
  transition: background-color 0.2s;
}
.clickable-row:hover {
  background-color: #f8f9fa;
}
</style>
