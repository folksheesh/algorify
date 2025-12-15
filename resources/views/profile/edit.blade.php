@extends('layouts.template')

@section('title', 'Algorify - Pengaturan Akun')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
        <link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">

@endpush

@section('content')
    <div class="settings-container">
        <div class="dashboard-container">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="settings-content">
                    <div class="settings-header">
                        <h1>Pengaturan Akun</h1>
                        <p>Kelola informasi pribadi, keamanan, dan preferensi akun Anda</p>
                    </div>

                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Profil berhasil diperbarui!
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-error">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                @foreach ($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Informasi Profil -->
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="settings-card">
                            <div class="card-header">
                                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <h2 class="card-title">Informasi Profil</h2>
                                    <p class="card-subtitle">Perbarui informasi profil dan foto Anda</p>
                                </div>
                            </div>

                            <div class="photo-section">
                                <div class="profile-avatar">
                                    @if(session()->has('temp_profile_photo') && \Storage::disk('public')->exists(session('temp_profile_photo')))
                                        <img src="{{ asset('storage/' . session('temp_profile_photo')) }}?v={{ time() }}" alt="Foto Profil" id="preview-image">
                                    @elseif($user->foto_profil && \Storage::disk('public')->exists($user->foto_profil))
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}?v={{ time() }}" alt="Foto Profil" id="preview-image">
                                    @else
                                        <span id="avatar-initial">{{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="photo-actions">
                                    <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/gif">
                                    <button type="button" class="btn-upload" onclick="document.getElementById('foto_profil').click()">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Ubah Foto
                                    </button>
                                    <span class="photo-hint">JPG, PNG atau GIF. Maks. 2MB</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-input @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-input @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890">
                                @error('phone')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bio</label>
                                <textarea class="form-input form-textarea @error('bio') is-invalid @enderror" name="bio" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                                @error('bio')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn-save">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Keamanan -->
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="settings-card">
                            <div class="card-header">
                                <svg class="card-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <div>
                                    <h2 class="card-title">Keamanan</h2>
                                    <p class="card-subtitle">Ubah password dan kelola keamanan akun</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-input @error('current_password') is-invalid @enderror" name="current_password" placeholder="Masukkan password saat ini">
                                @error('current_password')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-input @error('password') is-invalid @enderror" name="password" placeholder="Masukkan password baru">
                                @error('password')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-input @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="Konfirmasi password baru">
                                @error('password_confirmation')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn-save">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Ubah Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
    
    {{-- Footer --}}
    @include('components.footer')
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Load foto dari localStorage saat halaman dimuat
        (function() {
            const savedPhoto = localStorage.getItem('temp_profile_photo');
            console.log('Loading saved photo:', savedPhoto ? 'Found' : 'Not found');
            
            if (savedPhoto) {
                const avatar = document.querySelector('.profile-avatar');
                const initial = document.getElementById('avatar-initial');
                let existingImg = document.getElementById('preview-image');
                
                if (existingImg) {
                    existingImg.src = savedPhoto;
                } else {
                    if (initial) initial.style.display = 'none';
                    const img = document.createElement('img');
                    img.id = 'preview-image';
                    img.src = savedPhoto;
                    img.alt = 'Foto Profil';
                    img.style.width = '100%';
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '50%';
                    avatar.appendChild(img);
                }
            }
        })();

        // Preview foto profil saat pilih file
        document.getElementById('foto_profil').addEventListener('change', function(e) {
            const file = e.target.files[0];
            console.log('File selected:', file ? file.name : 'none');
            
            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB.');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const avatar = document.querySelector('.profile-avatar');
                    const initial = document.getElementById('avatar-initial');
                    let existingImg = document.getElementById('preview-image');
                    
                    // Simpan foto ke localStorage
                    try {
                        localStorage.setItem('temp_profile_photo', event.target.result);
                        console.log('Photo saved to localStorage');
                    } catch(err) {
                        console.error('Failed to save to localStorage:', err);
                    }
                    
                    if (existingImg) {
                        existingImg.src = event.target.result;
                    } else {
                        if (initial) initial.style.display = 'none';
                        const img = document.createElement('img');
                        img.id = 'preview-image';
                        img.src = event.target.result;
                        img.alt = 'Foto Profil';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        img.style.borderRadius = '50%';
                        avatar.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
