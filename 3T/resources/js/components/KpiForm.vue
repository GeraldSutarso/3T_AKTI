<template>
    <div>
      <h2>KPI Form for Current Period</h2>
      
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>No Room</th>
            <th>Kedisiplinan</th>
            <th>Kesehatan</th>
            <th>Safety</th>
            <th>5R</th>
            <th>7 Value Toyota</th>
            <th>Keterangan</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, index) in users" :key="index">
            <td>{{ index + 1 }}</td>
            <td>{{ user.name }}</td>
            <td>{{ user.student_id }}</td>
            <td><input v-model="user.kpi.no_room" type="text" class="form-control" /></td>
            <td><input v-model="user.kpi.kedisiplinan_point" type="number" class="form-control" /></td>
            <td><input v-model="user.kpi.kesehatan_point" type="number" class="form-control" /></td>
            <td><input v-model="user.kpi.safety_point" type="number" class="form-control" /></td>
            <td><input v-model="user.kpi.r5_point" type="number" class="form-control" /></td>
            <td><input v-model="user.kpi.vt7_point" type="number" class="form-control" /></td>
            <td><input v-model="user.kpi.keterangan" type="text" class="form-control" /></td>
          </tr>
        </tbody>
      </table>
  
      <button @click="saveKPI" class="btn btn-success">Save</button>
      <button @click="window.location.href = '/'" class="btn btn-secondary">Back</button>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        users: []
      };
    },
    methods: {
      async fetchUsers() {
        try {
          const response = await axios.get("/api/current-period-users");
          const users = response.data.users;
  
          this.users = users.map(user => ({
            ...user,
            kpi: user.kpis.length ? user.kpis[0] : {
              user_id: user.id,
              period_id: response.data.period.id,
              no_room: "",
              kedisiplinan_point: 0,
              kesehatan_point: 0,
              safety_point: 0,
              r5_point: 0,
              vt7_point: 0,
              keterangan: ""
            }
          }));
        } catch (error) {
          console.error("Error fetching users:", error);
        }
      },
      async saveKPI() {
        for (let user of this.users) {
          await axios.post("/api/kpis", user.kpi);
        }
        alert("Data saved successfully!");
      }
    },
    mounted() {
      this.fetchUsers();
    }
  };
  </script>
  