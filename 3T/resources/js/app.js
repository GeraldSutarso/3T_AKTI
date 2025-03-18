import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

// Register components
import ExampleComponent from './components/ExampleComponent.vue';
import Dashboard from './components/Dashboard.vue';
import ThreeTNavigation from './components/ThreeTNavigation.vue';
import ChartComponent from './components/ChartComponent.vue';
import NotificationWidget from './components/NotificationWidget.vue';

app.component('example-component', ExampleComponent);
app.component('dashboard', Dashboard);
app.component('three-t-navigation', ThreeTNavigation);
app.component('chart-component', ChartComponent);
app.component('notification-widget', NotificationWidget);

app.mount('#app');
