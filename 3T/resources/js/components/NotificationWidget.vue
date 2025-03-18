<template>
    <div ref="widget" class="notification-box" v-resizable v-draggable>
      <h3>Notifications</h3>
      <ul v-if="notifications.length">
        <li v-for="(notification, index) in notifications" :key="index" @click="openModal(notification)">
          {{ notification.text }}
        </li>
      </ul>
      <p v-else>No notifications</p>
  
      <!-- Modal -->
      <div v-if="selectedNotification" class="modal-overlay" @click="closeModal">
        <div class="modal-content" @click.stop>
          <h4>Notification Details</h4>
          <p><strong>User:</strong> {{ selectedNotification.name }}</p>
          <p><strong>Category:</strong> {{ selectedNotification.category }}</p>
          <p><strong>Issue:</strong> {{ selectedNotification.issue }}</p>
          <p><strong>Value:</strong> {{ selectedNotification.value }}</p>
          <button @click="closeModal">Close</button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        notifications: [],
        selectedNotification: null,
      };
    },
    methods: {
      async fetchNotifications() {
        try {
          const response = await axios.get("/api/notifications");
          this.notifications = [
            ...response.data.bodyData.map(n => ({ ...n, text: `${n.name} has BodyData issue` })),
            ...response.data.physical.map(n => ({ ...n, text: `${n.name} has Physical issue` })),
            ...response.data.kpi.map(n => ({ ...n, text: `${n.name} has KPI issue` })),
          ];
        } catch (error) {
          console.error("Error fetching notifications:", error);
        }
      },
      openModal(notification) {
        this.selectedNotification = notification;
      },
      closeModal() {
        this.selectedNotification = null;
      }
    },
    mounted() {
      this.fetchNotifications();
    }
  };
  </script>
  
  <style>
  /* Box Styling */
  .notification-box {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    background: #fff;
    min-width: 200px;
    min-height: 150px;
    max-width: 100%;
    overflow: auto;
    resize: both;
    position: absolute;
  }
  
  /* Notification List */
  .notification-box ul {
    list-style: none;
    padding: 0;
  }
  
  .notification-box li {
    cursor: pointer;
    padding: 5px;
    border-bottom: 1px solid #eee;
  }
  
  .notification-box li:hover {
    background: #f0f0f0;
  }
  
  /* Modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    width: 300px;
    text-align: center;
  }
  </style>
  