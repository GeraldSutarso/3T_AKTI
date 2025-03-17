import './bootstrap';
import { createApp } from 'vue';

const app = createApp({});

// Register components
import ExampleComponent from './components/ExampleComponent.vue';
import Dashboard from './components/Dashboard.vue';
import ThreeTNavigation from './components/ThreeTNavigation.vue';
app.component('example-component', ExampleComponent);
app.component('dashboard', Dashboard);
app.component('three-t-navigation', ThreeTNavigation);
app.mount('#app');
