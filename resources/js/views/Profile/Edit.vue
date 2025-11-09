<template>
  <div class="profile-edit">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pengaturan Akun</h1>

    <!-- Success Message -->
    <div v-if="successMessage" class="bg-green-50 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
      {{ successMessage }}
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
      <div v-if="typeof errorMessage === 'string'">{{ errorMessage }}</div>
      <ul v-else class="list-disc list-inside">
        <li v-for="(messages, field) in errorMessage" :key="field">
          {{ messages[0] }}
        </li>
      </ul>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Profile Photo Section -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Foto Profil</h2>
          <div class="flex flex-col items-center">
            <img
              :src="photoPreview"
              alt="Profile"
              class="w-32 h-32 rounded-full object-cover mb-4"
            />
            <input
              ref="photoInput"
              type="file"
              accept="image/*"
              @change="handlePhotoChange"
              class="hidden"
            />
            <button
              @click="$refs.photoInput.click()"
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Ubah Foto
            </button>
            <p class="text-xs text-gray-500 mt-2">JPG, PNG, atau GIF (Maks. 2MB)</p>
          </div>
        </div>
      </div>

      <!-- Profile Form -->
      <div class="lg:col-span-2">
        <form @submit.prevent="handleSubmit" class="bg-white rounded-lg shadow p-6 space-y-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
              <input
                v-model="form.phone"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Profesi</label>
              <input
                v-model="form.profesi"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
              <textarea
                v-model="form.address"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              ></textarea>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
              <input
                v-model="form.pendidikan"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <hr class="my-6" />

          <h2 class="text-lg font-semibold text-gray-900 mb-4">Ubah Password</h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
              <input
                v-model="form.password_lama"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
              <input
                v-model="form.password_baru"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
              <input
                v-model="form.password_baru_confirmation"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              :disabled="loading"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              {{ loading ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAuthStore } from '../../stores/auth';
import axios from '../../axios';

const authStore = useAuthStore();
const user = computed(() => authStore.user);

const form = ref({
  name: '',
  email: '',
  phone: '',
  profesi: '',
  address: '',
  pendidikan: '',
  foto_profil: null,
  password_lama: '',
  password_baru: '',
  password_baru_confirmation: ''
});

const photoInput = ref(null);
const photoPreview = ref('');
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

const handlePhotoChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.foto_profil = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const handleSubmit = async () => {
  loading.value = true;
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const formData = new FormData();
    
    // Append all form fields
    Object.keys(form.value).forEach(key => {
      if (key === 'foto_profil' && form.value[key] instanceof File) {
        formData.append(key, form.value[key]);
      } else if (form.value[key]) {
        formData.append(key, form.value[key]);
      }
    });

    const response = await axios.post('/profile', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    successMessage.value = 'Profil berhasil diperbarui!';
    
    // Update user in store
    await authStore.fetchUser();
    
    // Reset password fields
    form.value.password_lama = '';
    form.value.password_baru = '';
    form.value.password_baru_confirmation = '';

    // Scroll to top to show success message
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } catch (err) {
    errorMessage.value = err.response?.data?.errors || err.response?.data?.message || 'Gagal memperbarui profil';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (user.value) {
    form.value.name = user.value.name || '';
    form.value.email = user.value.email || '';
    form.value.phone = user.value.phone || '';
    form.value.profesi = user.value.profesi || '';
    form.value.address = user.value.address || '';
    form.value.pendidikan = user.value.pendidikan || '';
    
    photoPreview.value = user.value.foto_profil 
      ? `/storage/${user.value.foto_profil}` 
      : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.value.name)}`;
  }
});
</script>
