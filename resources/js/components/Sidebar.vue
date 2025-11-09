<template>
  <aside class="sidebar w-64 bg-white shadow-md flex flex-col h-screen fixed left-0 top-0">
    <!-- Logo / Brand -->
    <div class="logo p-6 border-b border-gray-200">
      <h1 class="text-2xl font-bold text-blue-600">Algorify</h1>
    </div>

    <!-- User Profile Section -->
    <div class="user-profile p-6 border-b border-gray-200" v-if="user">
      <div class="flex items-center space-x-3">
        <img 
          :src="userPhoto" 
          alt="Profile" 
          class="w-12 h-12 rounded-full object-cover"
        />
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-900 truncate">{{ user.name }}</p>
          <p class="text-xs text-gray-500 truncate">{{ user.email }}</p>
        </div>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto p-4">
      <ul class="space-y-2">
        <li>
          <router-link 
            to="/profile" 
            class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
            :class="{ 'active bg-blue-50 text-blue-600': $route.path === '/profile' }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Pengaturan Akun</span>
          </router-link>
        </li>
        <li>
          <router-link 
            to="/dashboard" 
            class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
            :class="{ 'active bg-blue-50 text-blue-600': $route.path === '/dashboard' }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Halaman Utama</span>
          </router-link>
        </li>
        <li>
          <router-link 
            to="/my-courses" 
            class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
            :class="{ 'active bg-blue-50 text-blue-600': $route.path === '/my-courses' }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Pelatihan Saya</span>
          </router-link>
        </li>
        <li>
          <router-link 
            to="/kursus" 
            class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
            :class="{ 'active bg-blue-50 text-blue-600': $route.path.startsWith('/kursus') }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span>Jelajahi Pelatihan</span>
          </router-link>
        </li>
        <li>
          <router-link 
            to="/certificates" 
            class="nav-link flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition-colors"
            :class="{ 'active bg-blue-50 text-blue-600': $route.path === '/certificates' }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span>Dapatkan Sertifikat</span>
          </router-link>
        </li>
      </ul>
    </nav>

    <!-- Logout Button -->
    <div class="p-4 border-t border-gray-200">
      <button 
        @click="handleLogout"
        class="w-full flex items-center justify-center px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span>Keluar</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const user = computed(() => authStore.user);

const userPhoto = computed(() => {
  if (user.value?.foto_profil) {
    return `/storage/${user.value.foto_profil}`;
  }
  return 'https://ui-avatars.com/api/?name=' + encodeURIComponent(user.value?.name || 'User');
});

const handleLogout = async () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    await authStore.logout();
    router.push('/login');
  }
};
</script>

<style scoped>
.nav-link.active {
  background-color: #EFF6FF;
  color: #2563EB;
  font-weight: 500;
}
</style>
