<template>
  <div class="dashboard-container">
    <Sidebar />
    <main class="main-content ml-64 p-8 bg-gray-50">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import Sidebar from '../components/Sidebar.vue';
import { onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();

onMounted(() => {
  // Fetch user data if not loaded
  if (!authStore.user) {
    authStore.fetchUser();
  }
});
</script>

<style scoped>
.dashboard-container {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  overflow-y: auto;
}
</style>
