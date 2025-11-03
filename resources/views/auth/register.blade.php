@extends('layouts.template')

@section('title', 'Register - Mazer Admin Dashboard')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/login.css') }}">
@endpush

@section('content')
    <div id="auth">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row h-100">
            <div class="col-lg-5 col-12">
                <div id="auth-left">
                    <div class="brand-row">
                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                            <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height: 41px; width:auto;">
                        </a>
                    </div>
                    <h1 class="auth-title">Buat Akun</h1>
                    <p class="auth-subtext mb-4">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>

                    <form method="POST" action="{{ route('register') }}" id="registerForm" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control form-control-xl" placeholder="Masukkan Nama Lengkap" name="name" id="name" value="{{ old('name') }}" required autocomplete="name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative has-icon-left">
                                <input type="email" class="form-control form-control-xl" placeholder="Masukkan Email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5" placeholder="Masukkan Kata Sandi" name="password" id="password" required autocomplete="new-password">
                                <button type="button" id="togglePassword" class="toggle-eye" tabindex="-1" aria-label="Tampilkan/Sembunyikan Kata Sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5" placeholder="Masukkan Kata Sandi" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                                <button type="button" id="togglePasswordConfirm" class="toggle-eye" tabindex="-1" aria-label="Tampilkan/Sembunyikan Konfirmasi Kata Sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control form-control-xl" placeholder="Masukkan Nomor Telepon" name="phone" id="phone" value="{{ old('phone') }}">
                        </div>

                        <div class="mb-3">
                            <label for="job" class="form-label">Profesi/Pekerjaan</label>
                            <input type="text" class="form-control form-control-xl" placeholder="Masukkan Profesi/Pekerjaan" name="job" id="job" value="{{ old('job') }}">
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Tanggal Lahir</label>
                            <div class="with-left-icon">
                                <input type="date" class="form-control form-control-xl" placeholder="Pilih Tanggal Lahir" name="birthdate" id="birthdate" value="{{ old('birthdate') }}">
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="agree" required>
                            <label class="form-check-label" for="agree">
                                Dengan klik Buat Akun, saya setuju pada <a href="#" target="_blank" rel="noopener">Syarat & Ketentuan</a> serta <a href="#" target="_blank" rel="noopener">Kebijakan Privasi</a>.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-2" id="submitBtn" disabled>Buat</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Force light theme on auth pages
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
    <script>
        (function(){
            const agree = document.getElementById('agree');
            const submitBtn = document.getElementById('submitBtn');
            const toggleBtn = document.getElementById('togglePassword');
            const pwdInput = document.getElementById('password');
            const toggleBtn2 = document.getElementById('togglePasswordConfirm');
            const pwdInput2 = document.getElementById('password_confirmation');
            if (agree && submitBtn) {
                const sync = () => submitBtn.disabled = !agree.checked;
                agree.addEventListener('change', sync);
                sync();
            }
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
            if (toggleBtn2 && pwdInput2) {
                toggleBtn2.addEventListener('click', function() {
                    const icon = toggleBtn2.querySelector('i');
                    const isPwd = pwdInput2.type === 'password';
                    pwdInput2.type = isPwd ? 'text' : 'password';
                    if (icon) {
                        icon.classList.toggle('bi-eye');
                        icon.classList.toggle('bi-eye-slash');
                    }
                });
            }
        })();
    </script>
@endpush
