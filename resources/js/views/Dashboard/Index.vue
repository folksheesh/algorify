<template>
  <!-- Admin Dashboard -->
  <AdminDashboard v-if="isAdmin" />

  <!-- Student Dashboard -->
  <div v-else class="dashboard">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Stats Cards -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div class="ml-5">
            <p class="text-sm font-medium text-gray-500">Pelatihan Aktif</p>
            <p class="text-2xl font-semibold text-gray-900">0</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-5">
            <p class="text-sm font-medium text-gray-500">Pelatihan Selesai</p>
            <p class="text-2xl font-semibold text-gray-900">0</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
          </div>
          <div class="ml-5">
            <p class="text-sm font-medium text-gray-500">Sertifikat</p>
            <p class="text-2xl font-semibold text-gray-900">0</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Welcome Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h2 class="text-xl font-semibold text-gray-900 mb-4">Selamat Datang di Algorify!</h2>
      <p class="text-gray-600 mb-4">
        Platform pembelajaran online untuk meningkatkan skill digital Anda. Mulai jelajahi pelatihan yang tersedia dan tingkatkan kemampuan Anda.
      </p>
      <router-link 
        to="/kursus" 
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        Jelajahi Pelatihan
        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </router-link>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-4">Aktivitas Terakhir</h2>
      <div class="text-center py-8 text-gray-500">
        <p>Belum ada aktivitas</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '../../stores/auth';
import AdminDashboard from '../Admin/Dashboard.vue';

const authStore = useAuthStore();
const user = computed(() => authStore.user);

// Check if user is admin (you can adjust this logic based on your role system)
const isAdmin = computed(() => {
  if (!user.value) return false;
  // Check if email is admin or has admin role
  return user.value.email === 'admin@algorify.com' || 
         user.value.email?.includes('admin') ||
         user.value.roles?.some(role => role.name === 'admin');
});
</script>
