<template>
  <div class="admin-dashboard">
    <div class="welcome-banner bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 text-white mb-6">
      <h1 class="text-3xl font-bold mb-2">Halo, Admin!</h1>
      <p class="text-blue-100">
        Selamat datang di halaman Admin. Kelola peserta, pengajar, course, dan transaksi dengan mudah untuk mendukung jalannya pelatihan TIK.
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-gray-600 text-sm font-medium">Total Peserta</h3>
          <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ stats.total_peserta || 0 }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-gray-600 text-sm font-medium">Total Pengajar</h3>
          <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ stats.total_pengajar || 0 }}</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-gray-600 text-sm font-medium">Total Kursus</h3>
          <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ stats.total_kursus || 0 }}</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Transaction Stats -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-gray-900">Transaksi</h2>
          <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option>Bulan ini</option>
            <option>Tahun ini</option>
          </select>
        </div>

        <div class="flex items-center justify-center mb-8">
          <!-- Donut Chart Placeholder -->
          <div class="relative w-48 h-48">
            <svg class="transform -rotate-90" viewBox="0 0 100 100">
              <!-- Background circle -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#e5e7eb" stroke-width="10"/>
              <!-- Transfer Bank - 77% -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#3b82f6" stroke-width="10"
                      stroke-dasharray="193 251" stroke-dashoffset="0"/>
              <!-- E-wallet - 50% (starting from 77%) -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#10b981" stroke-width="10"
                      stroke-dasharray="126 251" stroke-dashoffset="-193"/>
              <!-- Kartu Kredit - 48% -->
              <circle cx="50" cy="50" r="40" fill="none" stroke="#f59e0b" stroke-width="10"
                      stroke-dasharray="120 251" stroke-dashoffset="-319"/>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center flex-col">
              <p class="text-2xl font-bold text-gray-900">100%</p>
              <p class="text-xs text-gray-500">Total</p>
            </div>
          </div>
        </div>

        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full bg-blue-500"></div>
              <span class="text-sm text-gray-700">Transfer Bank</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">77%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full bg-green-500"></div>
              <span class="text-sm text-gray-700">E-wallet</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">50%</span>
          </div>
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
              <span class="text-sm text-gray-700">Kartu Kredit</span>
            </div>
            <span class="text-sm font-semibold text-gray-900">48%</span>
          </div>
        </div>
      </div>

      <!-- Growth Chart -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-semibold text-gray-900">Pertumbuhan Peserta</h2>
          <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option>Tahun ini</option>
            <option>Bulan ini</option>
          </select>
        </div>

        <!-- Bar Chart -->
        <div class="h-64 flex items-end justify-between gap-3">
          <div v-for="(data, day) in growthData" :key="day" class="flex-1 flex flex-col items-center gap-2">
            <div class="w-full flex flex-col gap-1 items-center">
              <!-- Revenue bar (cyan) -->
              <div class="w-full bg-cyan-400 rounded-t" :style="{ height: data.revenue + 'px' }"></div>
              <!-- Students bar (blue) -->
              <div class="w-full bg-blue-600 rounded-b" :style="{ height: data.students + 'px' }"></div>
            </div>
            <span class="text-xs text-gray-600 mt-2">{{ day }}</span>
          </div>
        </div>

        <div class="flex items-center justify-center gap-6 mt-6 pt-4 border-t">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded bg-cyan-400"></div>
            <span class="text-sm text-gray-600">Revenue</span>
          </div>
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 rounded bg-blue-600"></div>
            <span class="text-sm text-gray-600">Students</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from '../../axios';

const stats = ref({
  total_peserta: 0,
  total_pengajar: 0,
  total_kursus: 0
});

const growthData = ref({
  'S': { students: 60, revenue: 40 },
  'S': { students: 100, revenue: 70 },
  'R': { students: 70, revenue: 80 },
  'K': { students: 130, revenue: 90 },
  'J': { students: 90, revenue: 80 },
  'S': { students: 120, revenue: 100 },
  'M': { students: 100, revenue: 70 }
});

const fetchDashboardStats = async () => {
  try {
    const response = await axios.get('/admin/dashboard-stats');
    stats.value = response.data;
    if (response.data.growth_data) {
      growthData.value = response.data.growth_data;
    }
  } catch (error) {
    console.error('Error fetching admin stats:', error);
  }
};

onMounted(() => {
  fetchDashboardStats();
});
</script>

<style scoped>
.bg-gradient-to-r {
  background: linear-gradient(to right, #2563eb, #1e40af);
}
</style>
