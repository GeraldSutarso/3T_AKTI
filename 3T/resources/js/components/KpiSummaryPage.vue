<template>
    <div class="container-fluid">
      <!-- Header with logo placeholder -->
      <div class="row mb-4 align-items-center">
        <div class="col-2">
            <img src="/img/Logo (3295x1171).png" class="custom-logo" alt="Logo" />
        </div>

        <div class="col-10 text-center">
          <h2>SUMMARY NILAI KPI MAHASISWA SEMESTER {{ currentPeriod.semester }} A#{{ currentGeneration.gen }}</h2>
        </div>
      </div>
  
      <!-- Main table -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="bg-light">
            <tr>
              <th rowspan="2" class="align-middle text-center">No</th>
              <th rowspan="2" class="align-middle text-center">Nama</th>
              <th rowspan="2" class="align-middle text-center">NIM</th>
              <th rowspan="2" class="align-middle text-center">Angkatan</th>
              <th rowspan="2" class="align-middle text-center">Program Studi</th>
              <th rowspan="2" class="align-middle text-center">Semester</th>
              <th rowspan="2" class="align-middle text-center">No Room</th>
              <th colspan="2" class="text-center">Kedisiplinan</th>
              <th colspan="2" class="text-center">Kesehatan</th>
              <th colspan="2" class="text-center">Safety</th>
              <th colspan="2" class="text-center">R5</th>
              <th colspan="2" class="text-center">VT7</th>
              <th rowspan="2" class="align-middle text-center">Keterangan</th>
            </tr>
            <tr>
              <th class="text-center">Point</th>
              <th class="text-center">Nilai</th>
              <th class="text-center">Point</th>
              <th class="text-center">Nilai</th>
              <th class="text-center">Point</th>
              <th class="text-center">Nilai</th>
              <th class="text-center">Point</th>
              <th class="text-center">Nilai</th>
              <th class="text-center">Point</th>
              <th class="text-center">Nilai</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="user.id">
              <td class="text-center">{{ index + 1 }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.student_id }}</td>
              <td class="text-center">{{ user.group.generations.gen }}</td>
              <td>{{ user.group.prodi }}</td>
              <td class="text-center">{{ currentPeriod.semester }}</td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.no_room" 
                  @blur="updateKpi(user.id, 'no_room', user.kpi.no_room)"
                >
              </td>
              <td>
                <input 
                  type="number" 
                  class="form-control form-control-sm" 
                  v-model.number="user.kpi.kedisiplinan_point" 
                  @blur="updateKpi(user.id, 'kedisiplinan_point', user.kpi.kedisiplinan_point)"
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.kedisiplinan_nilai" 
                  :class="getValueClass('kedisiplinan', user.kpi.kedisiplinan_nilai)"
                  readonly
                >
              </td>
              <td>
                <input 
                  type="number" 
                  class="form-control form-control-sm" 
                  v-model.number="user.kpi.kesehatan_point" 
                  @blur="updateKpi(user.id, 'kesehatan_point', user.kpi.kesehatan_point)"
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.kesehatan_nilai" 
                  :class="getValueClass('kesehatan', user.kpi.kesehatan_nilai)"
                  readonly
                >
              </td>
              <td>
                <input 
                  type="number" 
                  class="form-control form-control-sm" 
                  v-model.number="user.kpi.safety_point" 
                  @blur="updateKpi(user.id, 'safety_point', user.kpi.safety_point)"
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.safety_nilai" 
                  :class="getValueClass('safety', user.kpi.safety_nilai)"
                  readonly
                >
              </td>
              <td>
                <input 
                  type="number" 
                  class="form-control form-control-sm" 
                  v-model.number="user.kpi.r5_point" 
                  @blur="updateKpi(user.id, 'r5_point', user.kpi.r5_point)"
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.r5_nilai" 
                  :class="getValueClass('r5', user.kpi.r5_nilai)"
                  readonly
                >
              </td>
              <td>
                <input 
                  type="number" 
                  class="form-control form-control-sm" 
                  v-model.number="user.kpi.vt7_point" 
                  @blur="updateKpi(user.id, 'vt7_point', user.kpi.vt7_point)"
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.vt7_nilai" 
                  :class="getValueClass('vt7', user.kpi.vt7_nilai)"
                  readonly
                >
              </td>
              <td>
                <input 
                  type="text" 
                  class="form-control form-control-sm" 
                  v-model="user.kpi.keterangan" 
                  @blur="updateKpi(user.id, 'keterangan', user.kpi.keterangan)"
                >
              </td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="18" class="pt-3">
                <div class="row">
                  <div class="col-md-4 text-center">
                    <p>Prepared</p>
                    <p class="mt-5">{{ preparedBy }}</p>
                  </div>
                  <div class="col-md-4 text-center">
                    <p>Checked</p>
                    <p class="mt-5">{{ checkedBy }}</p>
                  </div>
                  <div class="col-md-4 text-center">
                    <p>Approved</p>
                    <p class="mt-5">{{ approvedBy }}</p>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan="18">
                <div class="row">
                  <div class="col-md-3">
                    <strong>NOTE:</strong>
                    <div>Kedisiplinan:</div>
                    <div>A: {{ thresholds.kedisiplinan_min_a }} - 44</div>
                    <div>B: {{ thresholds.kedisiplinan_min_b }} - {{ thresholds.kedisiplinan_min_a - 1 }}</div>
                    <div>C: {{ thresholds.kedisiplinan_min_c }} - {{ thresholds.kedisiplinan_min_b - 1 }}</div>
                    <div>D: {{ thresholds.kedisiplinan_min_d }} - {{ thresholds.kedisiplinan_min_c - 1 }}</div>
                  </div>
                  <div class="col-md-3">
                    <strong>NOTE:</strong>
                    <div>Kesehatan:</div>
                    <div>A: {{ thresholds.kesehatan_min_a }} - 44</div>
                    <div>B: {{ thresholds.kesehatan_min_b }} - {{ thresholds.kesehatan_min_a - 1 }}</div>
                    <div>C: {{ thresholds.kesehatan_min_c }} - {{ thresholds.kesehatan_min_b - 1 }}</div>
                    <div>D: {{ thresholds.kesehatan_min_d }} - {{ thresholds.kesehatan_min_c - 1 }}</div>
                  </div>
                  <div class="col-md-3">
                    <strong>NOTE:</strong>
                    <div>Safety:</div>
                    <div>A: {{ thresholds.safety_min_a }} - 44</div>
                    <div>B: {{ thresholds.safety_min_b }} - {{ thresholds.safety_min_a - 1 }}</div>
                    <div>C: {{ thresholds.safety_min_c }} - {{ thresholds.safety_min_b - 1 }}</div>
                    <div>D: {{ thresholds.safety_min_d }} - {{ thresholds.safety_min_c - 1 }}</div>
                  </div>
                  <div class="col-md-3">
                    <strong>NOTE:</strong>
                    <div>R5:</div>
                    <div>A: {{ thresholds.r5_min_a }} - 56</div>
                    <div>B: {{ thresholds.r5_min_b }} - {{ thresholds.r5_min_a - 1 }}</div>
                    <div>C: {{ thresholds.r5_min_c }} - {{ thresholds.r5_min_b - 1 }}</div>
                    <div>D: {{ thresholds.r5_min_d }} - {{ thresholds.r5_min_c - 1 }}</div>
                  </div>
                </div>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
  
      <!-- Loading overlay -->
      <div class="loading-overlay" v-if="loading">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'KpiManagement',
    data() {
      return {
        users: [],
        currentPeriod: {},
        currentGeneration: {},
        thresholds: {},
        loading: true,
        preparedBy: 'Raysa',
        checkedBy: 'M Juliarto',
        approvedBy: 'Mursyid'
      };
    },
    async created() {
      await this.fetchData();
    },
    methods: {
      async fetchData() {
        try {
          this.loading = true;
          
          // Fetch current period
          const periodResponse = await axios.get('/api/periods/current');
          this.currentPeriod = periodResponse.data;
          
          // Fetch current generation based on period
          const generationResponse = await axios.get(`/api/generations/${this.currentPeriod.gen_id}`);
          this.currentGeneration = generationResponse.data;
          
          // Fetch thresholds
          const thresholdsResponse = await axios.get('/api/thresholds');
          this.thresholds = thresholdsResponse.data;
          
          // Fetch users with their KPI data
          const usersResponse = await axios.get('/api/users', {
            params: {
              period_id: this.currentPeriod.id,
              exclude_groups: [99, 100, 101, 102]
            }
          });
          
          this.users = usersResponse.data.map(user => {
            // If KPI doesn't exist, create empty object
            if (!user.kpi) {
              user.kpi = {
                user_id: user.id,
                no_room: '',
                kedisiplinan_point: null,
                kedisiplinan_nilai: '',
                kesehatan_point: null,
                kesehatan_nilai: '',
                safety_point: null,
                safety_nilai: '',
                r5_point: null,
                r5_nilai: '',
                vt7_point: null,
                vt7_nilai: '',
                keterangan: ''
              };
            }
            return user;
          });
          
          // Calculate nilai for each KPI field
          this.users.forEach(user => {
            this.calculateNilai(user);
          });
        } catch (error) {
          console.error('Error fetching data:', error);
          // Handle error appropriately (e.g., show error message)
        } finally {
          this.loading = false;
        }
      },
      
      calculateNilai(user) {
        // Calculate nilai for each category based on point value
        const calculateGrade = (category, point) => {
          if (!point) return '';
          
          const minA = this.thresholds[`${category}_min_a`];
          const minB = this.thresholds[`${category}_min_b`];
          const minC = this.thresholds[`${category}_min_c`];
          const minD = this.thresholds[`${category}_min_d`];
          
          if (point >= minA) return 'A';
          if (point >= minB) return 'B';
          if (point >= minC) return 'C';
          if (point >= minD) return 'D';
          return 'D';
        };
        
        user.kpi.kedisiplinan_nilai = calculateGrade('kedisiplinan', user.kpi.kedisiplinan_point);
        user.kpi.kesehatan_nilai = calculateGrade('kesehatan', user.kpi.kesehatan_point);
        user.kpi.safety_nilai = calculateGrade('safety', user.kpi.safety_point);
        user.kpi.r5_nilai = calculateGrade('r5', user.kpi.r5_point);
        user.kpi.vt7_nilai = calculateGrade('vt7', user.kpi.vt7_point);
      },
      
      async updateKpi(userId, field, value) {
        try {
          const user = this.users.find(u => u.id === userId);
          if (!user) return;
          
          // Update the local data
          user.kpi[field] = value;
          
          // Recalculate nilai if point is updated
          if (field.includes('point')) {
            this.calculateNilai(user);
          }
          
          // Send update to server
          await axios.post('/api/kpi/update', {
            user_id: userId,
            period_id: this.currentPeriod.id,
            [field]: value
          });
          
          // If point is updated, also update the corresponding nilai
          if (field.includes('point')) {
            const category = field.split('_')[0];
            const nilaiField = `${category}_nilai`;
            
            await axios.post('/api/kpi/update', {
              user_id: userId,
              period_id: this.currentPeriod.id,
              [nilaiField]: user.kpi[nilaiField]
            });
          }
        } catch (error) {
          console.error('Error updating KPI:', error);
          // Handle error appropriately (e.g., show error message)
        }
      },
      
      getValueClass(category, value) {
        if (!value) return '';
        
        const valueClasses = {
          'A': 'bg-success text-white',
          'B': 'bg-info text-white',
          'C': 'bg-warning',
          'D': 'bg-danger text-white'
        };
        
        return valueClasses[value] || '';
      }
    }
  };
  </script>
  
  <style scoped>
  .loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }
  
  .table th, .table td {
    vertical-align: middle;
    padding: 0.5rem;
  }
  
  .table input.form-control-sm {
    padding: 0.25rem 0.5rem;
    height: calc(1.5em + 0.5rem + 2px);
  }
  
  .table input.form-control-sm[readonly] {
    text-align: center;
    font-weight: bold;
  }
  .custom-logo {
      width: 150px;
      height: auto;
  }
  </style>