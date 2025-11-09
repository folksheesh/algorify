<template>
  <div class="my-courses">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pelatihan Saya</h1>

    <div class="bg-white rounded-lg shadow p-8 text-center">
      <svg class="mx-auto h-24 w-24 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
      </svg>
      <h2 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Pelatihan</h2>
      <p class="text-gray-600 mb-6">Anda belum mendaftar pada pelatihan apapun. Mulai jelajahi pelatihan yang tersedia!</p>
      <router-link
        to="/kursus"
        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
      >
        Jelajahi Pelatihan
        <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </router-link>
    </div>
  </div>
</template>

<script setup>
</script>
