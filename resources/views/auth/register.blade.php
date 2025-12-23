@extends('layouts.template')

@section('title', 'Daftar - Algorify')

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
                            <input type="text" class="form-control form-control-xl @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap" name="name" id="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="position-relative has-icon-left">
                                <input type="email" class="form-control form-control-xl @error('email') is-invalid @enderror" placeholder="Masukkan email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username">
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata Sandi</label>
                            <div class="password-wrap">
                                <input type="password" class="form-control form-control-xl pe-5 @error('password') is-invalid @enderror" placeholder="Masukkan kata sandi" name="password" id="password" required autocomplete="new-password">
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
                                <input type="password" class="form-control form-control-xl pe-5 @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi kata sandi" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
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
                            <input type="number" class="form-control form-control-xl @error('phone') is-invalid @enderror" placeholder="Masukkan nomor telepon" name="phone" id="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profesi" class="form-label">Profesi/Pekerjaan</label>
                            <input type="text" class="form-control form-control-xl @error('profesi') is-invalid @enderror" placeholder="Masukkan profesi/pekerjaan" name="profesi" id="profesi" value="{{ old('profesi') }}" required>
                            @error('profesi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control form-control-xl @error('tanggal_lahir') is-invalid @enderror" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control form-control-xl @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin" required>
                                <option value="">Pilih jenis kelamin</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat (Kabupaten/Kota)</label>
                            <select class="form-control form-control-xl @error('address') is-invalid @enderror" name="address" id="address" required>
                                <option value="">Pilih kabupaten/kota</option>
                            </select>
                            @error('address')
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
    <script src="{{ asset('js/indonesia-cities.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Populate kabupaten/kota dropdown
            const addressSelect = $('#address');
            const oldAddress = "{{ old('address') }}";
            
            // Add cities to select
            indonesiaCities.forEach(function(city) {
                const option = new Option(city, city, false, city === oldAddress);
                addressSelect.append(option);
            });
            
            // Initialize Select2 with search
            addressSelect.select2({
                placeholder: 'Pilih kabupaten/kota',
                allowClear: true,
                width: '100%',
                matcher: function(params, data) {
                    // If there are no search terms, return all data
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    // Do not display the item if there is no 'text' property
                    if (typeof data.text === 'undefined') {
                        return null;
                    }

                    // `params.term` is the user's search term
                    // `data.text` is the text that is displayed for the data object
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }

                    // Return `null` if the term should not be displayed
                    return null;
                }
            });
        });
    </script>
@endpush
