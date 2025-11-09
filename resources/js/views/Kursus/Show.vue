<template>
  <div class="kursus-detail">
    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      <p class="mt-4 text-gray-600">Memuat detail pelatihan...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>

    <!-- Content -->
    <div v-else-if="kursus" class="max-w-4xl">
      <!-- Back Button -->
      <router-link
        to="/kursus"
        class="inline-flex items-center text-blue-600 hover:text-blue-700 mb-6"
      >
        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Pelatihan
      </router-link>

      <!-- Course Header -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <img
          :src="kursus.thumbnail || '/template/img/default-course.jpg'"
          :alt="kursus.judul"
          class="w-full h-64 object-cover"
        />
        <div class="p-6">
          <span class="inline-block px-3 py-1 text-sm font-semibold text-blue-600 bg-blue-100 rounded mb-3">
            {{ kursus.kategori }}
          </span>
          <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ kursus.judul }}</h1>
          <p class="text-gray-600 mb-4">{{ kursus.deskripsi_singkat }}</p>
          <div class="flex items-center justify-between border-t pt-4">
            <span class="text-3xl font-bold text-blue-600">
              Rp {{ formatPrice(kursus.harga) }}
            </span>
            <button
              @click="handleEnroll"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold"
            >
              Daftar Sekarang
            </button>
          </div>
        </div>
      </div>

      <!-- Course Details -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Tentang Pelatihan</h2>
        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ kursus.deskripsi }}</p>
      </div>

      <!-- Instructor -->
      <div v-if="kursus.pengajar" class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Pengajar</h2>
        <div class="flex items-center">
          <img
            :src="kursus.pengajar.foto_profil ? `/storage/${kursus.pengajar.foto_profil}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(kursus.pengajar.name)}`"
            :alt="kursus.pengajar.name"
            class="w-16 h-16 rounded-full object-cover mr-4"
          />
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ kursus.pengajar.name }}</h3>
            <p class="text-gray-600">{{ kursus.pengajar.profesi || 'Instruktur' }}</p>
          </div>
        </div>
      </div>

      <!-- Modules -->
      <div v-if="kursus.modul && kursus.modul.length > 0" class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Modul Pembelajaran</h2>
        <div class="space-y-3">
          <div
            v-for="(modul, index) in kursus.modul"
            :key="modul.id"
            class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-colors"
          >
            <h3 class="font-semibold text-gray-900">
              Modul {{ index + 1 }}: {{ modul.judul }}
            </h3>
            <p class="text-sm text-gray-600 mt-1">{{ modul.deskripsi }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useKursusStore } from '../../stores/kursus';

const route = useRoute();
const router = useRouter();
const kursusStore = useKursusStore();

const kursus = computed(() => kursusStore.currentKursus);
const loading = computed(() => kursusStore.loading);
const error = computed(() => kursusStore.error);

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const handleEnroll = () => {
  alert('Fitur pendaftaran akan segera tersedia!');
};

onMounted(async () => {
  try {
    await kursusStore.fetchKursusById(route.params.id);
  } catch (err) {
    console.error('Error fetching kursus detail:', err);
  }
});
</script>
