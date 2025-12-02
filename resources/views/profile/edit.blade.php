@extends('layouts.template')

@section('title', 'Algorify - Edit Profil Peserta')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .profile-edit-container {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .profile-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .profile-header {
            background: #5D3FFF;
            color: white;
            padding: 2rem;
            border-radius: 12px 12px 0 0;
        }

        .profile-header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
        }

        .profile-header p {
            margin: 0;
            opacity: 0.9;
        }

        .profile-form-container {
            background: white;
            padding: 2rem;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .profile-photo-section {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #6b7280;
            margin-right: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .upload-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #5D3FFF;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid white;
        }

        .upload-icon svg {
            width: 14px;
            height: 14px;
            color: white;
        }

        .photo-info h3 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
        }

        .photo-info p {
            font-size: 0.875rem;
            color: #6b7280;
            margin: 0;
        }

        .upload-button {
            background: transparent;
            border: none;
            color: #5D3FFF;
            font-weight: 500;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: background 0.2s;
        }

        .upload-button:hover {
            background: #f3f4f6;
        }

        #foto_profil {
            display: none;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: #f9fafb;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #5D3FFF;
            background: white;
            box-shadow: 0 0 0 3px rgba(93, 63, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-start;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-primary {
            background: #5D3FFF;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #4c32cc;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: #6b7280;
            border: 1px solid #e5e7eb;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Responsive untuk tablet */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .profile-content {
                padding: 1rem;
            }
            
            .profile-header {
                padding: 1.5rem;
            }
            
            .profile-header h1 {
                font-size: 1.5rem;
            }
            
            .profile-form-container {
                padding: 1.5rem;
            }
            
            .profile-photo-section {
                flex-direction: column;
                text-align: center;
            }
            
            .profile-avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .profile-content {
                padding: 0.75rem;
            }
            
            .profile-header {
                padding: 1.25rem;
                border-radius: 10px 10px 0 0;
            }
            
            .profile-header h1 {
                font-size: 1.25rem;
            }
            
            .profile-form-container {
                padding: 1rem;
                border-radius: 0 0 10px 10px;
            }
            
            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 0.625rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="profile-edit-container">
        <div class="dashboard-container">
            @include('components.sidebar')
            
            <main class="main-content" style="background: #f8f9fa;">
                <div class="profile-content">
                    <div class="profile-header">
                        <h1>Edit Profil Peserta</h1>
                        <p>Perbarui informasi profil Anda</p>
                    </div>
                    
                    <div class="profile-form-container">
                        @if (session('status') === 'profile-updated')
                            <div class="alert alert-success">
                                Profil berhasil diperbarui!
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-error">
                                <ul style="margin: 0; padding-left: 1.25rem;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

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
                                <div class="photo-info">
                                    <h3>Foto Profil</h3>
                                    <p>JPG, PNG atau GIF. Maksimal 2MB</p>
                                    <input type="file" id="foto_profil" name="foto_profil" accept="image/jpeg,image/png,image/gif">
                                    <button type="button" class="upload-button" onclick="document.getElementById('foto_profil').click()">
                                        Upload Foto
                                    </button>
                                </div>
                            </div>

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
                                    <label for="profesi">Profesi</label>
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

                                <div class="form-group">
                                    <label for="password_lama">Ganti Kata Sandi Lama</label>
                                    <input type="password" id="password_lama" name="password_lama" placeholder="Masukkan Kata Sandi Lama">
                                </div>

                                <div class="form-group">
                                    <label for="password_baru">Ganti Kata Sandi Baru</label>
                                    <input type="password" id="password_baru" name="password_baru" placeholder="Masukkan Kata Sandi Baru">
                                </div>

                                <div class="form-group full-width">
                                    <label for="password_baru_confirmation">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="password_baru_confirmation" name="password_baru_confirmation" placeholder="Konfirmasi Kata Sandi Baru">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('dashboard') }}" class="btn-secondary" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Force light theme
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
                        avatar.insertBefore(img, avatar.firstChild);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
