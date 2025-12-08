@extends('layouts.template')

@section('title', 'Lupa Kata Sandi - Algorify')

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

                    <h1 class="auth-title">Lupa Kata Sandi?</h1>
                    <p class="auth-subtext mb-4">Masukkan email Anda dan kami akan mengirimkan link untuk mereset kata sandi</p>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative has-icon-left">
                                <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Masukkan Email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">
                            <i class="bi bi-envelope me-2"></i>Kirim Link Reset Password
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted">Ingat kata sandi Anda? 
                                <a href="{{ route('login') }}" class="fw-semibold">Kembali ke Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="Forgot Password Illustration" class="login-illustration">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Ensure light theme on auth pages
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
@endpush
