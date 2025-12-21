@extends('layouts.template')

@section('title', 'Lengkapi Profil - Algorify')

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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
            color: #6c757d !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .select2-dropdown {
            border: 1px solid #dfe3e7 !important;
            border-radius: 0.5rem !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #435EBE !important;
        }
        .select2-results__option {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
            padding: 10px 12px !important;
        }
        .select2-search--dropdown .select2-search__field {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
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
                    <h1 class="auth-title">Lengkapi Profil</h1>
                    <p class="auth-subtext mb-4">Isi data berikut untuk mulai menggunakan Algorify.</p>

                    @if (session('status'))
                        <div class="alert alert-info">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.complete.store') }}" id="completeProfileForm" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control form-control-xl" value="{{ $user->name }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control form-control-xl" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control form-control-xl @error('phone') is-invalid @enderror"
                                   placeholder="Masukkan nomor telepon" name="phone" id="phone"
                                   value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profesi" class="form-label">Profesi/Pekerjaan</label>
                            <input type="text" class="form-control form-control-xl @error('profesi') is-invalid @enderror"
                                   placeholder="Masukkan profesi/pekerjaan" name="profesi" id="profesi"
                                   value="{{ old('profesi', $user->profesi) }}" required>
                            @error('profesi')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control form-control-xl @error('tanggal_lahir') is-invalid @enderror"
                                   name="tanggal_lahir" id="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', optional($user->tanggal_lahir)->format('Y-m-d')) }}" required>
                            @error('tanggal_lahir')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control form-control-xl @error('jenis_kelamin') is-invalid @enderror"
                                    name="jenis_kelamin" id="jenis_kelamin" required>
                                <option value="">Pilih jenis kelamin</option>
                                <option value="L" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat (Kabupaten/Kota)</label>
                            <select class="form-control form-control-xl @error('address') is-invalid @enderror"
                                    name="address" id="address" required>
                                <option value="">Pilih kabupaten/kota</option>
                            </select>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">Simpan &amp; Lanjutkan</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="Complete Profile Illustration" class="login-illustration">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/indonesia-cities.js') }}"></script>
    <script>
        $(document).ready(function() {
            const addressSelect = $('#address');
            const oldAddress = "{{ old('address', $user->address) }}";

            indonesiaCities.forEach(function(city) {
                const option = new Option(city, city, false, city === oldAddress);
                addressSelect.append(option);
            });

            // Initialize Select2 for address
            addressSelect.select2({
                placeholder: 'Pilih kabupaten/kota',
                allowClear: true,
                width: '100%',
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }
                    if (typeof data.text === 'undefined') {
                        return null;
                    }
                    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }
                    return null;
                }
            });
            
            // Initialize Select2 for jenis_kelamin
            $('#jenis_kelamin').select2({
                placeholder: 'Pilih jenis kelamin',
                allowClear: false,
                width: '100%',
                minimumResultsForSearch: Infinity // Hide search box for short lists
            });
        });
    </script>
@endpush
