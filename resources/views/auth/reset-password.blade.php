@extends('layouts.template')

@section('title', 'Reset Password - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/login.css') }}">
@endpush

@section('content')
    <div id="auth">
        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="brand-row">
                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                            <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height: 40px; width:auto;">
                        </a>
                    </div>

                    <h1 class="auth-title">Buat Password Baru</h1>
                    <p class="auth-subtext mb-4">Masukkan password baru untuk akun <strong>{{ $email }}</strong></p>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.store') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror" 
                                       placeholder="Masukkan password baru" name="password" id="password" required autofocus>
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2" 
                                        onclick="togglePassword('password', 'eyeIcon1')" style="border: none; background: none;">
                                    <i class="bi bi-eye" id="eyeIcon1"></i>
                                </button>
                            </div>
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control form-control-xl @error('password_confirmation') is-invalid @enderror" 
                                       placeholder="Konfirmasi password baru" name="password_confirmation" id="password_confirmation" required>
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y me-2" 
                                        onclick="togglePassword('password_confirmation', 'eyeIcon2')" style="border: none; background: none;">
                                    <i class="bi bi-eye" id="eyeIcon2"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                            <i class="bi bi-shield-lock me-2"></i>Reset Password
                        </button>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-muted">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="Reset Password Illustration" class="login-illustration">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
@endpush
