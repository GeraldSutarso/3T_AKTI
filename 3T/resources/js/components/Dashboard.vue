<template>
  <div>
    <h1>Dashboard</h1>
    <p>Welcome to your Vue-powered Laravel dashboard.</p>

    <!-- 3T Navigation Widget -->
    <ThreeTNavigation />

    <!-- Add other widgets later -->
  </div>
</template>

<script>
import ThreeTNavigation from './ThreeTNavigation.vue';

export default {
  name: 'Dashboard',
  components: {
    ThreeTNavigation, // Register the component here
  }
};
</script>

<style scoped>
h1 {
  color: #333;
}
</style>
