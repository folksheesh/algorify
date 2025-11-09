<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Daftar Akun Baru
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
          Atau
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
            masuk ke akun yang sudah ada
          </router-link>
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div v-if="error" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded">
          <div v-if="typeof error === 'string'">{{ error }}</div>
          <ul v-else class="list-disc list-inside">
            <li v-for="(messages, field) in error" :key="field">
              {{ messages[0] }}
            </li>
          </ul>
        </div>
        
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="name" class="sr-only">Nama Lengkap</label>
            <input 
              id="name" 
              v-model="form.name" 
              type="text" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="Nama Lengkap"
            />
          </div>
          <div>
            <label for="email" class="sr-only">Email</label>
            <input 
              id="email" 
              v-model="form.email" 
              type="email" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="Email"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input 
              id="password" 
              v-model="form.password" 
              type="password" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="Password (min. 8 karakter)"
            />
          </div>
          <div>
            <label for="password_confirmation" class="sr-only">Konfirmasi Password</label>
            <input 
              id="password_confirmation" 
              v-model="form.password_confirmation" 
              type="password" 
              required 
              class="appearance-none rounded-lg relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
              placeholder="Konfirmasi Password"
            />
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
          >
            {{ loading ? 'Memproses...' : 'Daftar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const error = ref(null);
const loading = ref(false);

const handleRegister = async () => {
  loading.value = true;
  error.value = null;
  
  try {
    await authStore.register(form.value);
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.errors || err.response?.data?.message || 'Registrasi gagal. Silakan coba lagi.';
  } finally {
    loading.value = false;
  }
};
</script>
