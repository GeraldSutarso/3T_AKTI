<template>
  <div class="container-fluid kpi-container">
    <!-- Header -->
    <div class="row mb-4 align-items-center kpi-header">
      <div class="col-2">
        <img src="/img/Logo (3295x1171).png" style="width: 150px;" class="custom-logo" alt="Logo" />
      </div>
      <div class="col-10 text-center">
        <h2>SUMMARY NILAI KPI MAHASISWA SEMESTER {{ currentPeriod.semester }} A#{{ currentGeneration }}</h2>
      </div>
    </div>

    <!-- KPI Form Table -->
    <div class="table-responsive kpi-table-wrapper">
      <table class="table table-bordered table-striped kpi-table">
        <thead class="bg-light">
          <tr>
            <th rowspan="2" class="text-center">No</th>
            <th rowspan="2" class="text-center">Nama</th>
            <th rowspan="2" class="text-center">NIM</th>
            <th rowspan="2" class="text-center">Angkatan</th>
            <th rowspan="2" class="text-center">Program Studi</th>
            <th rowspan="2" class="text-center">Semester</th>
            <th rowspan="2" class="text-center">No Room</th>
            <th colspan="2" class="text-center">Kedisiplinan</th>
            <th colspan="2" class="text-center">Kesehatan</th>
            <th colspan="2" class="text-center">Safety</th>
            <th colspan="2" class="text-center">R5</th>
            <th colspan="2" class="text-center">VT7</th>
            <th rowspan="2" class="text-center">Keterangan</th>
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
            <td>{{ currentGeneration }}</td>
            <td>{{ user.group?.prodi || 'N/A' }}</td>
            <td>{{ currentPeriod.semester }}</td>
            <td>
              <input type="text" v-model="user.kpi.no_room" class="form-control" />
            </td>
            <td>
              <input type="text" v-model="user.kpi.kedisiplinan_point" class="form-control"
                @input="updateNilai(user, 'kedisiplinan')" />
            </td>
            <td class="text-center">{{ user.kpi.kedisiplinan_nilai }}</td>
            <td>
              <input type="text" v-model="user.kpi.kesehatan_point" class="form-control"
                @input="updateNilai(user, 'kesehatan')" />
            </td>
            <td class="text-center">{{ user.kpi.kesehatan_nilai }}</td>
            <td>
              <input type="text" v-model="user.kpi.safety_point" class="form-control"
                @input="updateNilai(user, 'safety')" />
            </td>
            <td class="text-center">{{ user.kpi.safety_nilai }}</td>
            <td>
              <input type="text" v-model="user.kpi.r5_point" class="form-control"
                @input="updateNilai(user, 'r5')" />
            </td>
            <td class="text-center">{{ user.kpi.r5_nilai }}</td>
            <td>
              <input type="text" v-model="user.kpi.vt7_point" class="form-control"
                @input="updateNilai(user, 'vt7')" />
            </td>
            <td class="text-center">{{ user.kpi.vt7_nilai }}</td>
            <td>
              <textarea v-model="user.kpi.keterangan" class="form-control"></textarea>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Minimum Value Ranges -->
<div class="kpi-min-values">
  <h5>📌 Nilai Range</h5>
  <table class="table table-bordered text-center">
    <thead class="bg-light">
      <tr>
        <th v-for="(value, key) in formattedMinimumValues" :key="key">{{ key }}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td v-for="(value, key) in formattedMinimumValues" :key="'A' + key">A: {{ value.A }}</td>
      </tr>
      <tr>
        <td v-for="(value, key) in formattedMinimumValues" :key="'B' + key">B: {{ value.B }}</td>
      </tr>
      <tr>
        <td v-for="(value, key) in formattedMinimumValues" :key="'C' + key">C: {{ value.C }}</td>
      </tr>
      <tr>
        <td v-for="(value, key) in formattedMinimumValues" :key="'D' + key">D: {{ value.D }}</td>
      </tr>
    </tbody>
  </table>
</div>


    <!-- Floating Save Button -->
  <button class="btn btn-primary save-button" @click="saveKpi">
    💾
  </button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: [],
      currentPeriod: {},
      currentGeneration: '',
      minimumValues: {},
    };
  },
  computed: {
  formattedMinimumValues() {
    const kpiFields = {
      kedisiplinan: 'Kedisiplinan',
      kesehatan: 'Kesehatan',
      safety: 'Safety',
      r5: '5R',
      vt7: '7 Value Toyota'
    };

    return Object.keys(kpiFields).reduce((obj, key) => {
      const minA = this.minimumValues[`${key}_min_a`] || 0;
      const minB = this.minimumValues[`${key}_min_b`] || 0;
      const minC = this.minimumValues[`${key}_min_c`] || 0;
      const minD = this.minimumValues[`${key}_min_d`] || 0;

      obj[kpiFields[key]] = {
        A: `${minA}+`,  
        B: `${minB} - ${minA - 1}`,
        C: `${minC} - ${minB - 1}`,
        D: `${minD} - ${minC - 1}`
      };

      return obj;
    }, {});
  }
},
  methods: {
    async fetchData() {
      try {
        const [usersRes, periodRes, minValuesRes] = await Promise.all([
          axios.get('/kpi/users'),
          axios.get('/kpi/period'),
          axios.get('/kpi/minimum-values')
        ]);

        this.users = usersRes.data.map(user => ({
          ...user,
          kpi: user.kpi || {
            no_room: '',
            kedisiplinan_point: '',
            kedisiplinan_nilai: '',
            kesehatan_point: '',
            kesehatan_nilai: '',
            safety_point: '',
            safety_nilai: '',
            r5_point: '',
            r5_nilai: '',
            vt7_point: '',
            vt7_nilai: '',
            keterangan: ''
          }
        }));

        this.currentPeriod = periodRes.data;
        this.minimumValues = minValuesRes.data;

        // Fetch generation name
        const genId = this.currentPeriod.gen_id;
        const genRes = await axios.get(`/kpi/generation/${genId}`);
        this.currentGeneration = genRes.data.gen;

      } catch (error) {
        console.error("Error fetching data:", error);
      }
    }
  },
  async mounted() {
    await this.fetchData();
  }
};
</script>



<style>
.kpi-container {
  border: 2px solid black;
  padding: 15px;
}
.kpi-header {
  border-bottom: 2px solid black;
}
.kpi-table-wrapper {
  border: 2px solid black;
  overflow-x: auto;
}
.kpi-min-values {
  border: 1px solid black;
  padding: 10px;
  margin-top: 10px;
  background-color: #f8f9fa;
}

/* Floating Save Button */
.save-button {
  position: fixed;
  top: 100px; /* Adjust this value to position it below the navbar */
  right: 30px;
  z-index: 50;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
</style>
