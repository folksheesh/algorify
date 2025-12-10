{{-- Gunakan layout utama --}}
@extends('layouts.template')

{{-- Judul halaman --}}
@section('title', 'Algorify - Edit Profil ' . (str_starts_with(auth()->user()->id, 'PJR') ? 'Pengajar' : 'Peserta'))

{{-- Tambahkan stylesheet dan style khusus halaman --}}
@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile/index.css') }}">

@endpush

@section('content')
    {{-- Konten utama halaman profil --}}
    <div class="profile-edit-container">
        <div class="dashboard-container">
            {{-- Tampilkan sidebar --}}
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="profile-content">
                    {{-- Header halaman --}}
                    <div class="profile-header">
                        <h1>Edit Profil {{ str_starts_with(auth()->user()->id, 'PJR') ? 'Pengajar' : 'Peserta' }}</h1>
                        <p>Perbarui informasi profil Anda</p>
                    </div>
                    
                    <div class="profile-form-container">
                        {{-- Pesan sukses --}}
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success">
                                Profil berhasil diperbarui!
                            </div>
                        @endif

                        {{-- Pesan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-error">
                                <ul style="margin: 0; padding-left: 1.25rem;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                    {{-- Form update profil: CSRF, method PATCH, upload foto --}}
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- Kontrol upload foto dan preview --}}
                            <div class="profile-photo-section">
                                <div class="profile-avatar">
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" id="preview-image">
                                    @else
                                        <span id="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                    <label for="foto_profil" class="upload-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </label>
                                </div>
                                {{-- Kontrol upload foto dan tombol upload --}}
                                <div class="photo-info">
                                    <h3>Foto Profil</h3>
                                    <p>JPG, PNG atau GIF. Maksimal 2MB</p>
                                    <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/gif">
                                    <button type="button" class="upload-button" onclick="document.getElementById('foto_profil').click()">
                                        Upload Foto
                                    </button>
                                </div>
                            </div>

                            {{-- Input profil (nama, email, telepon, pekerjaan, dsb.) --}}
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required placeholder="Prashant Kumar Singh">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="prashant@example.com">
                                </div>

                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812 3456 7890">
                                </div>

                                <div class="form-group">
                                    <label for="profesi">Pekerjaan</label>
                                    <input type="text" id="profesi" name="profesi" value="{{ old('profesi', $user->profesi) }}" placeholder="Software Developer">
                                </div>

                                <div class="form-group full-width">
                                    <label for="address">Alamat</label>
                                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" placeholder="Jakarta, Indonesia">
                                </div>

                                <div class="form-group full-width">
                                    <label for="pendidikan">Pendidikan</label>
                                    <input type="text" id="pendidikan" name="pendidikan" value="{{ old('pendidikan', $user->pendidikan) }}" placeholder="Sarjana Komputer">
                                </div>

                                <div class="form-group full-width">
                                    <label for="password_lama">Ganti Kata Sandi Lama</label>
                                    <input type="password" id="password_lama" name="password_lama" placeholder="Masukkan Kata Sandi Lama">
                                </div>

                                <div class="form-group">
                                    <label for="password_baru">Ganti Kata Sandi Baru</label>
                                    <input type="password" id="password_baru" name="password_baru" placeholder="Masukkan Kata Sandi Baru">
                                </div>

                                <div class="form-group">
                                    <label for="password_baru_confirmation">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" placeholder="Konfirmasi Kata Sandi Baru">
                                </div>
                            </div>

                            {{-- Tombol simpan dan batal --}}
                            <div class="form-actions">
                                <button type="submit" class="btn-primary" style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Simpan Perubahan</button>
                                <a href="{{ route('dashboard') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

{{-- Script khusus halaman:
     - menetapkan tema light untuk elemen root
     - menangani preview gambar saat user memilih file foto profil
     Letakkan di stack scripts agar ditempatkan sebelum penutupan body oleh layout --}}
@push('scripts')
    <script>
        // Paksa tema light pada root (beberapa layout menggunakan attribute ini untuk tema)
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Preview foto profil: menampilkan preview segera setelah file dipilih
        document.getElementById('foto_profil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatar = document.querySelector('.profile-avatar');
                    const initial = document.getElementById('avatar-initial');
                    const existingImg = document.getElementById('preview-image');
                    
                    if (existingImg) {
                        existingImg.src = e.target.result;
                    } else {
                        if (initial) initial.remove();
                        const img = document.createElement('img');
                        img.id = 'preview-image';
                        img.src = e.target.result;
                        img.alt = 'Foto Profil';
                        avatar.insertBefore(img, avatar.firstChild);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
