<template>
    <div class="container-fluid">
      <div class="row mb-3">
        <div class="col-12">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <img src="/placeholder-logo.png" alt="Logo" style="height: 60px; width: 60px;">
            </div>
            <h2 class="mb-0">SUMMARY NILAI KPI MAHASISWA SEMESTER {{ currentPeriod.semester }} A#{{ currentGeneration.gen }}</h2>
          </div>
        </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-12">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead class="table-light">
                <tr>
                  <th rowspan="2" class="align-middle text-center">No</th>
                  <th rowspan="2" class="align-middle text-center">Nama Mahasiswa</th>
                  <th rowspan="2" class="align-middle text-center">NIM</th>
                  <th rowspan="2" class="align-middle text-center">Angkatan</th>
                  <th rowspan="2" class="align-middle text-center">Program Studi</th>
                  <th rowspan="2" class="align-middle text-center">Semester</th>
                  <th rowspan="2" class="align-middle text-center">No room</th>
                  <th colspan="2" class="text-center">Kedisiplinan</th>
                  <th colspan="2" class="text-center">Kesehatan</th>
                  <th colspan="2" class="text-center">Safety 5R</th>
                  <th colspan="2" class="text-center">7 Value Toyota</th>
                  <th rowspan="2" class="align-middle text-center">Keterangan</th>
                </tr>
                <tr>
                  <th class="text-center">Total point</th>
                  <th class="text-center">Nilai</th>
                  <th class="text-center">Total point</th>
                  <th class="text-center">Nilai</th>
                  <th class="text-center">Total point</th>
                  <th class="text-center">Nilai</th>
                  <th class="text-center">Total point</th>
                  <th class="text-center">Nilai</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(user, index) in users" :key="user.id">
                  <td class="text-center">{{ index + 1 }}</td>
                  <td>{{ user.name }}</td>
                  <td>{{ user.student_id }}</td>
                  <td class="text-center">{{ user.group.generations.gen }}</td>
                  <td class="text-center">{{ user.group.prodi }}</td>
                  <td class="text-center">{{ currentPeriod.semester }}</td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].no_room"
                      @change="saveKpiData(user.id, 'no_room')"
                    >
                  </td>
                  <td>
                    <input 
                      type="number" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].kedisiplinan_point"
                      @change="saveKpiData(user.id, 'kedisiplinan_point')"
                    >
                  </td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].kedisiplinan_nilai"
                      @change="saveKpiData(user.id, 'kedisiplinan_nilai')"
                      :class="getGradeClass(kpiData[user.id].kedisiplinan_nilai)"
                    >
                  </td>
                  <td>
                    <input 
                      type="number" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].kesehatan_point"
                      @change="saveKpiData(user.id, 'kesehatan_point')"
                    >
                  </td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].kesehatan_nilai"
                      @change="saveKpiData(user.id, 'kesehatan_nilai')"
                      :class="getGradeClass(kpiData[user.id].kesehatan_nilai)"
                    >
                  </td>
                  <td>
                    <input 
                      type="number" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].safety_point"
                      @change="saveKpiData(user.id, 'safety_point')"
                    >
                  </td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].safety_nilai"
                      @change="saveKpiData(user.id, 'safety_nilai')"
                      :class="getGradeClass(kpiData[user.id].safety_nilai)"
                    >
                  </td>
                  <td>
                    <input 
                      type="number" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].vt7_point"
                      @change="saveKpiData(user.id, 'vt7_point')"
                    >
                  </td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].vt7_nilai"
                      @change="saveKpiData(user.id, 'vt7_nilai')"
                      :class="getGradeClass(kpiData[user.id].vt7_nilai)"
                    >
                  </td>
                  <td>
                    <input 
                      type="text" 
                      class="form-control form-control-sm" 
                      v-model="kpiData[user.id].keterangan"
                      @change="saveKpiData(user.id, 'keterangan')"
                    >
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="7" class="text-end">NOTE:</td>
                  <td colspan="2">
                    A: 40 - 44<br>
                    B: 32 - 39<br>
                    C: 24 - 31<br>
                    D: 16 - 23
                  </td>
                  <td colspan="2">
                    A: 40 - 44<br>
                    B: 32 - 39<br>
                    C: 24 - 31<br>
                    D: 16 - 23
                  </td>
                  <td colspan="2">
                    A: 40 - 44<br>
                    B: 32 - 39<br>
                    C: 24 - 31<br>
                    D: 16 - 23
                  </td>
                  <td colspan="2">
                    A: 50 - 56<br>
                    B: 42 - 49<br>
                    C: 34 - 41<br>
                    D: 26 - 33
                  </td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      
      <div class="row mt-4">
        <div class="col-4 text-center">
          <p>Prepared</p>
          <p class="mt-5">_____________</p>
        </div>
        <div class="col-4 text-center">
          <p>Checked</p>
          <p class="mt-5">_____________</p>
        </div>
        <div class="col-4 text-center">
          <p>Approved</p>
          <p class="mt-5">_____________</p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        users: [],
        kpiData: {},
        currentPeriod: {},
        currentGeneration: {},
        loading: true,
        errorMessage: ''
      }
    },
    
    created() {
      this.fetchData();
    },
    
    methods: {
      fetchData() {
        this.loading = true;
        
        // Fetch current period
        axios.get('/api/periods/current')
          .then(response => {
            this.currentPeriod = response.data;
            return axios.get('/api/users');
          })
          .then(response => {
            // Filter out users with group_id 99,100,101,102
            this.users = response.data.filter(user => 
              ![99, 100, 101, 102].includes(user.group_id)
            );
            
            // Get generation info
            if (this.users.length > 0 && this.users[0].group && this.users[0].group.generations) {
              this.currentGeneration = this.users[0].group.generations;
            }
            
            // Initialize KPI data structure
            this.initializeKpiData();
            
            // Fetch existing KPI data
            return axios.get(`/api/kpi?period_id=${this.currentPeriod.id}`);
          })
          .then(response => {
            // Update KPI data with existing values
            const existingData = response.data;
            existingData.forEach(kpi => {
              if (this.kpiData[kpi.user_id]) {
                this.kpiData[kpi.user_id] = {
                  ...this.kpiData[kpi.user_id],
                  ...kpi
                };
              }
            });
            
            this.loading = false;
          })
          .catch(error => {
            console.error('Error fetching data:', error);
            this.errorMessage = 'Failed to load data. Please try again.';
            this.loading = false;
          });
      },
      
      initializeKpiData() {
        // Initialize empty KPI data structure for each user
        this.users.forEach(user => {
          this.kpiData[user.id] = {
            user_id: user.id,
            period_id: this.currentPeriod.id,
            no_room: '',
            kedisiplinan_point: null,
            kedisiplinan_nilai: '',
            kesehatan_point: null,
            kesehatan_nilai: '',
            safety_point: null,
            safety_nilai: '',
            vt7_point: null,
            vt7_nilai: '',
            keterangan: ''
          };
        });
      },
      
      saveKpiData(userId, field) {
        const kpi = this.kpiData[userId];
        
        // If the field is a point field, automatically determine the grade
        if (field.includes('point')) {
          const category = field.split('_')[0];
          const nilai = this.calculateGrade(category, kpi[field]);
          kpi[`${category}_nilai`] = nilai;
        }
        
        // Save the KPI data
        axios.post('/api/kpi', kpi)
          .then(response => {
            // Update the local data with the response
            this.kpiData[userId] = response.data;
            
            // Show a success message (you might want to use a toast notification here)
            console.log('KPI data saved successfully');
          })
          .catch(error => {
            console.error('Error saving KPI data:', error);
            // Show an error message
          });
      },
      
      calculateGrade(category, points) {
        if (!points) return '';
        
        // Different grading scales based on the category
        if (category === 'vt7') {
          if (points >= 50 && points <= 56) return 'A';
          if (points >= 42 && points <= 49) return 'B';
          if (points >= 34 && points <= 41) return 'C';
          if (points >= 26 && points <= 33) return 'D';
        } else {
          if (points >= 40 && points <= 44) return 'A';
          if (points >= 32 && points <= 39) return 'B';
          if (points >= 24 && points <= 31) return 'C';
          if (points >= 16 && points <= 23) return 'D';
        }
        
        return '';
      },
      
      getGradeClass(grade) {
        if (!grade) return '';
        
        switch(grade) {
          case 'A': return 'bg-success text-white';
          case 'B': return 'bg-info text-white';
          case 'C': return 'bg-warning text-dark';
          case 'D': return 'bg-danger text-white';
          default: return '';
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .table-responsive {
    overflow-x: auto;
  }
  
  .table {
    font-size: 0.9rem;
  }
  
  input.form-control-sm {
    padding: 0.25rem 0.5rem;
    height: calc(1.5em + 0.5rem + 2px);
  }
  
  .bg-success, .bg-info, .bg-warning, .bg-danger {
    opacity: 0.8;
  }
  </style>