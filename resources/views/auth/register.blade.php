@extends('layouts.template')

@section('title', 'Register - Mazer Admin Dashboard')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            padding: 8px 12px !important;
            border: 1px solid #dfe3e7 !important;
            border-radius: 0.5rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            color: #6c757d !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .select2-dropdown {
            border: 1px solid #dfe3e7 !important;
            border-radius: 0.5rem !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #435EBE !important;
        }
    </style>
@endpush

@section('content')
    <div id="auth">
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
                            <input type="text" class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Masukkan Nama Lengkap" name="name" id="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative has-icon-left">
                                <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Masukkan Email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5 @error('password') is-invalid @enderror" placeholder="Masukkan Kata Sandi" name="password" id="password" required autocomplete="new-password">
                                <button type="button" id="togglePassword" class="toggle-eye" tabindex="-1" aria-label="Tampilkan/Sembunyikan Kata Sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5 @error('password_confirmation') is-invalid @enderror" placeholder="Masukkan Kata Sandi" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                                <button type="button" id="togglePasswordConfirm" class="toggle-eye" tabindex="-1" aria-label="Tampilkan/Sembunyikan Konfirmasi Kata Sandi">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control form-control-xl @error('phone') is-invalid @enderror" placeholder="Masukkan Nomor Telepon" name="phone" id="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="job" class="form-label">Profesi/Pekerjaan</label>
                            <input type="text" class="form-control form-control-xl @error('job') is-invalid @enderror" placeholder="Masukkan Profesi/Pekerjaan" name="job" id="job" value="{{ old('job') }}">
                            @error('job')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Tanggal Lahir</label>
                            <div class="with-left-icon">
                                <input type="date" class="form-control form-control-xl @error('birthdate') is-invalid @enderror" placeholder="Pilih Tanggal Lahir" name="birthdate" id="birthdate" value="{{ old('birthdate') }}">
                            </div>
                            @error('birthdate')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control form-control-xl @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="domisili" class="form-label">Domisili (Provinsi)</label>
                            <select class="form-control form-control-xl @error('domisili') is-invalid @enderror" name="domisili" id="domisili">
                                <option value="">Pilih Provinsi</option>
                                <option value="Aceh" {{ old('domisili') == 'Aceh' ? 'selected' : '' }}>Aceh</option>
                                <option value="Sumatera Utara" {{ old('domisili') == 'Sumatera Utara' ? 'selected' : '' }}>Sumatera Utara</option>
                                <option value="Sumatera Barat" {{ old('domisili') == 'Sumatera Barat' ? 'selected' : '' }}>Sumatera Barat</option>
                                <option value="Riau" {{ old('domisili') == 'Riau' ? 'selected' : '' }}>Riau</option>
                                <option value="Kepulauan Riau" {{ old('domisili') == 'Kepulauan Riau' ? 'selected' : '' }}>Kepulauan Riau</option>
                                <option value="Jambi" {{ old('domisili') == 'Jambi' ? 'selected' : '' }}>Jambi</option>
                                <option value="Sumatera Selatan" {{ old('domisili') == 'Sumatera Selatan' ? 'selected' : '' }}>Sumatera Selatan</option>
                                <option value="Bangka Belitung" {{ old('domisili') == 'Bangka Belitung' ? 'selected' : '' }}>Bangka Belitung</option>
                                <option value="Bengkulu" {{ old('domisili') == 'Bengkulu' ? 'selected' : '' }}>Bengkulu</option>
                                <option value="Lampung" {{ old('domisili') == 'Lampung' ? 'selected' : '' }}>Lampung</option>
                                <option value="DKI Jakarta" {{ old('domisili') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                <option value="Banten" {{ old('domisili') == 'Banten' ? 'selected' : '' }}>Banten</option>
                                <option value="Jawa Barat" {{ old('domisili') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                <option value="Jawa Tengah" {{ old('domisili') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                <option value="DI Yogyakarta" {{ old('domisili') == 'DI Yogyakarta' ? 'selected' : '' }}>DI Yogyakarta</option>
                                <option value="Jawa Timur" {{ old('domisili') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                <option value="Bali" {{ old('domisili') == 'Bali' ? 'selected' : '' }}>Bali</option>
                                <option value="Nusa Tenggara Barat" {{ old('domisili') == 'Nusa Tenggara Barat' ? 'selected' : '' }}>Nusa Tenggara Barat</option>
                                <option value="Nusa Tenggara Timur" {{ old('domisili') == 'Nusa Tenggara Timur' ? 'selected' : '' }}>Nusa Tenggara Timur</option>
                                <option value="Kalimantan Barat" {{ old('domisili') == 'Kalimantan Barat' ? 'selected' : '' }}>Kalimantan Barat</option>
                                <option value="Kalimantan Tengah" {{ old('domisili') == 'Kalimantan Tengah' ? 'selected' : '' }}>Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan" {{ old('domisili') == 'Kalimantan Selatan' ? 'selected' : '' }}>Kalimantan Selatan</option>
                                <option value="Kalimantan Timur" {{ old('domisili') == 'Kalimantan Timur' ? 'selected' : '' }}>Kalimantan Timur</option>
                                <option value="Kalimantan Utara" {{ old('domisili') == 'Kalimantan Utara' ? 'selected' : '' }}>Kalimantan Utara</option>
                                <option value="Sulawesi Utara" {{ old('domisili') == 'Sulawesi Utara' ? 'selected' : '' }}>Sulawesi Utara</option>
                                <option value="Sulawesi Tengah" {{ old('domisili') == 'Sulawesi Tengah' ? 'selected' : '' }}>Sulawesi Tengah</option>
                                <option value="Sulawesi Selatan" {{ old('domisili') == 'Sulawesi Selatan' ? 'selected' : '' }}>Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara" {{ old('domisili') == 'Sulawesi Tenggara' ? 'selected' : '' }}>Sulawesi Tenggara</option>
                                <option value="Gorontalo" {{ old('domisili') == 'Gorontalo' ? 'selected' : '' }}>Gorontalo</option>
                                <option value="Sulawesi Barat" {{ old('domisili') == 'Sulawesi Barat' ? 'selected' : '' }}>Sulawesi Barat</option>
                                <option value="Maluku" {{ old('domisili') == 'Maluku' ? 'selected' : '' }}>Maluku</option>
                                <option value="Maluku Utara" {{ old('domisili') == 'Maluku Utara' ? 'selected' : '' }}>Maluku Utara</option>
                                <option value="Papua" {{ old('domisili') == 'Papua' ? 'selected' : '' }}>Papua</option>
                                <option value="Papua Barat" {{ old('domisili') == 'Papua Barat' ? 'selected' : '' }}>Papua Barat</option>
                                <option value="Papua Tengah" {{ old('domisili') == 'Papua Tengah' ? 'selected' : '' }}>Papua Tengah</option>
                                <option value="Papua Pegunungan" {{ old('domisili') == 'Papua Pegunungan' ? 'selected' : '' }}>Papua Pegunungan</option>
                                <option value="Papua Selatan" {{ old('domisili') == 'Papua Selatan' ? 'selected' : '' }}>Papua Selatan</option>
                                <option value="Papua Barat Daya" {{ old('domisili') == 'Papua Barat Daya' ? 'selected' : '' }}>Papua Barat Daya</option>
                            </select>
                            @error('domisili')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
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
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="Register Illustration" class="login-illustration">
                </div>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#domisili').select2({
                placeholder: 'Pilih Provinsi',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
