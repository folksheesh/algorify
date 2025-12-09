@extends('layouts.template')

@section('title', 'Algorify - Pengaturan Akun')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .settings-container {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .settings-content {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }

        .settings-header {
            margin-bottom: 1.5rem;
        }

        .settings-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 0.5rem 0;
        }

        .settings-header p {
            color: #6b7280;
            margin: 0;
            font-size: 0.9rem;
        }

        .settings-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        .card-icon {
            width: 24px;
            height: 24px;
            color: #5D3FFF;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        .card-subtitle {
            font-size: 0.8rem;
            color: #6b7280;
            margin: 0.25rem 0 0 0;
        }

        /* Photo Section */
        .photo-section {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3A6DFF 0%, #3A6DFF 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-actions {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-upload {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-upload:hover {
            background: #f9fafb;
            border-color: #5D3FFF;
            color: #5D3FFF;
        }

        .photo-hint {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        #foto_profil {
            display: none;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #1a1a2e;
            background: #fafafa;
            transition: all 0.2s;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #5D3FFF;
            background: white;
            box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Button Styles */
        .card-footer {
            display: flex;
            justify-content: flex-end;
            padding-top: 1.25rem;
            margin-top: 1.25rem;
            border-top: 1px solid #f0f0f0;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #3A6DFF;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-save:hover {
            background: #4c32cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3);
        }

        .btn-save svg {
            width: 18px;
            height: 18px;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0 !important;
                padding: 20px !important;
                padding-top: 80px !important;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 16px !important;
                padding-top: 70px !important;
            }

            .settings-content {
                max-width: 100%;
            }

            .settings-card {
                padding: 1.25rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .photo-section {
                flex-direction: column;
                text-align: center;
            }

            .photo-actions {
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 12px !important;
                padding-top: 65px !important;
            }

            .settings-header h1 {
                font-size: 1.25rem;
            }

            .settings-card {
                padding: 1rem;
                border-radius: 12px;
            }

            .form-input {
                padding: 0.625rem 0.875rem;
                font-size: 0.85rem;
            }
        }
    </style>
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
                                    @if($user->foto_profil)
                                        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" id="preview-image">
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
                                <input type="text" class="form-input" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-input" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-input" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Bio</label>
                                <textarea class="form-input form-textarea" name="bio" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
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
                                <input type="password" class="form-input" name="current_password" placeholder="Masukkan password saat ini">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-input" name="password" placeholder="Masukkan password baru">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-input" name="password_confirmation" placeholder="Konfirmasi password baru">
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

        // Preview foto profil
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
                        avatar.appendChild(img);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
