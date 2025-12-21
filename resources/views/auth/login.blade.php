@extends('layouts.template')

@section('title', 'Masuk - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/login.css') }}">
@endpush

@section('content')
    <div id="auth">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if (session('oauth_error'))
            <div class="alert alert-danger">{{ session('oauth_error') }}</div>
        @endif

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="brand-row">
                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                            <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height: 40px; width:auto;">
                        </a>
                    </div>

                    <h1 class="auth-title">Masuk</h1>
                    <p class="auth-subtext mb-4">Pengguna Baru? <a href="{{ route('register') }}" class="fw-semibold">Buat Akun</a></p>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative has-icon-left">
                                <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Masukkan Email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label mb-0">Kata Sandi</label>
                            </div>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5 @error('password') is-invalid @enderror" placeholder="Masukkan Kata Sandi" name="password" id="password" required autocomplete="current-password">
                                <button type="button" id="togglePassword" class="toggle-eye" tabindex="-1" aria-label="Tampilkan/Sembunyikan Kata Sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <div class="form-control-icon d-none">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberEmail" value="1">
                                <label class="form-check-label" for="rememberEmail">Ingat email saya</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="link-forgot" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-3">Masuk</button>

                        <div class="divider">
                            <hr>
                            <span>Atau</span>
                            <hr>
                        </div>

                        <a href="{{ url('/auth/google') }}" class="btn btn-google w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="22" height="22" aria-hidden="true">
                                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12 s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C33.64,6.053,29.083,4,24,4C12.955,4,4,12.955,4,24 s8.955,20,20,20s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,16.108,18.961,13,24,13c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657 C33.64,6.053,29.083,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.197l-6.19-5.238C29.211,35.091,26.715,36,24,36 c-5.202,0-9.619-3.317-11.283-7.946l-6.563,5.047C9.48,39.556,16.227,44,24,44z"/>
                                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.094,5.565 c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                            </svg>
                            <span>Masuk dengan Google</span>
                        </a>

                        <p class="disclaimer mb-0">
                            Dilindungi oleh reCAPTCHA dan tunduk pada <a href="https://policies.google.com/privacy?hl=id" target="_blank" rel="noopener">Kebijakan Privasi</a> serta <a href="https://policies.google.com/terms?hl=id" target="_blank" rel="noopener">Ketentuan Layanan</a> Google.
                        </p>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="Login Illustration" class="login-illustration">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Ensure light theme on auth pages (in case previous page set dark)
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
    <script>
        (function(){
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');
            const emailInput = document.getElementById('email');
            const rememberCheck = document.getElementById('rememberEmail');
            const loginForm = document.querySelector('form');
            
            // Toggle password visibility
            if (toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function() {
                    const icon = toggleBtn.querySelector('i');
                    const isPwd = pwdInput.type === 'password';
                    pwdInput.type = isPwd ? 'text' : 'password';
                    if (icon) {
                        icon.classList.toggle('bi-eye');
                        icon.classList.toggle('bi-eye-slash');
                    }
                });
            }
            
            // Load saved email from localStorage
            const savedEmail = localStorage.getItem('remembered_email');
            if (savedEmail && emailInput) {
                emailInput.value = savedEmail;
                if (rememberCheck) rememberCheck.checked = true;
            }
            
            // Save email to localStorage on form submit if checkbox is checked
            if (loginForm) {
                loginForm.addEventListener('submit', function() {
                    if (rememberCheck && rememberCheck.checked && emailInput) {
                        localStorage.setItem('remembered_email', emailInput.value);
                    } else {
                        localStorage.removeItem('remembered_email');
                    }
                });
            }
        })();
    </script>
@endpush
