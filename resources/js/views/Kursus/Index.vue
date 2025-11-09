<template>
  <div class="kursus-page">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Jelajahi Pelatihan</h1>

    <!-- Search and Filter Section -->
    <div class="mb-8 space-y-4">
      <!-- Search Box -->
      <div class="relative">
        <input
          v-model="searchQuery"
          @input="handleSearch"
          type="text"
          placeholder="Cari pelatihan..."
          class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <svg
          class="absolute left-4 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>

      <!-- Category Filter -->
      <div class="flex flex-wrap gap-2">
        <button
          @click="selectCategory('')"
          :class="[
            'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
            selectedCategory === '' 
              ? 'bg-blue-600 text-white' 
              : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
          ]"
        >
          Semua
        </button>
        <button
          v-for="category in categories"
          :key="category"
          @click="selectCategory(category)"
          :class="[
            'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
            selectedCategory === category 
              ? 'bg-blue-600 text-white' 
              : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50'
          ]"
        >
          {{ category }}
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      <p class="mt-4 text-gray-600">Memuat pelatihan...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
      {{ error }}
    </div>

    <!-- Kursus Grid -->
    <div v-else>
      <div v-if="kursus.length === 0" class="text-center py-12 text-gray-500">
        <p>Tidak ada pelatihan ditemukan</p>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <div
          v-for="course in kursus"
          :key="course.id"
          class="course-card bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 flex flex-col overflow-hidden"
        >
          <!-- Thumbnail -->
          <div class="course-thumbnail h-[180px] overflow-hidden bg-gray-200">
            <img
              :src="course.thumbnail || '/template/img/default-course.jpg'"
              :alt="course.judul"
              class="w-full h-full object-cover object-center"
            />
          </div>

          <!-- Content -->
          <div class="p-4 flex flex-col flex-1">
            <!-- Category Badge -->
            <span class="inline-block px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded mb-2 w-fit">
              {{ course.kategori }}
            </span>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
              {{ course.judul }}
            </h3>

            <!-- Description -->
            <p class="text-sm text-gray-600 mb-4 line-clamp-2 flex-1">
              {{ course.deskripsi_singkat || course.deskripsi }}
            </p>

            <!-- Price and Button -->
            <div class="flex items-center justify-between mt-auto">
              <span class="text-lg font-bold text-blue-600">
                Rp {{ formatPrice(course.harga) }}
              </span>
              <router-link
                :to="`/kursus/${course.id}`"
                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
              >
                Lihat Detail
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination.last_page > 1" class="flex justify-center items-center space-x-2">
        <button
          @click="changePage(pagination.current_page - 1)"
          :disabled="pagination.current_page === 1"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Sebelumnya
        </button>
        
        <span class="px-4 py-2 text-gray-700">
          Halaman {{ pagination.current_page }} dari {{ pagination.last_page }}
        </span>
        
        <button
          @click="changePage(pagination.current_page + 1)"
          :disabled="pagination.current_page === pagination.last_page"
          class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Selanjutnya
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useKursusStore } from '../../stores/kursus';

const kursusStore = useKursusStore();

const searchQuery = ref('');
const selectedCategory = ref('');
const categories = [
  'Analisis Data',
  'Keamanan Siber',
  'UI/UX Design',
  'IT Support',
  'Web Development',
  'Mobile Development',
  'Digital Marketing',
  'AI/Machine Learning',
  'Cloud Computing',
  'Blockchain'
];

const kursus = computed(() => kursusStore.kursus);
const loading = computed(() => kursusStore.loading);
const error = computed(() => kursusStore.error);
const pagination = computed(() => kursusStore.pagination);

const formatPrice = (price) => {
  return new Intl.NumberFormat('id-ID').format(price);
};

const selectCategory = (category) => {
  selectedCategory.value = category;
  kursusStore.setFilter('kategori', category);
  fetchKursus();
};

const handleSearch = () => {
  kursusStore.setFilter('search', searchQuery.value);
  fetchKursus();
};

const changePage = (page) => {
  fetchKursus(page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const fetchKursus = async (page = 1) => {
  try {
    await kursusStore.fetchKursus(page);
  } catch (err) {
    console.error('Error fetching kursus:', err);
  }
};

onMounted(() => {
  fetchKursus();
});
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.course-card {
  min-height: 340px;
}
</style>
