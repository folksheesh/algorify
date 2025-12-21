@extends('layouts.template')

@section('title', 'Verifikasi OTP - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/extensions/bootstrap-icons/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/custom/login.css') }}">
    <style>
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid #dfe3e7;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .otp-input:focus {
            border-color: #435EBE;
            box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.15);
            outline: none;
        }
        .timer {
            text-align: center;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .timer span {
            font-weight: 600;
            color: #435EBE;
        }
        .resend-btn {
            background: none;
            border: none;
            color: #435EBE;
            font-weight: 600;
            cursor: pointer;
        }
        .resend-btn:disabled {
            color: #ccc;
            cursor: not-allowed;
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
                            <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height: 40px; width:auto;">
                        </a>
                    </div>

                    <h1 class="auth-title">Verifikasi OTP</h1>
                    <p class="auth-subtext mb-4">Masukkan kode 6 digit yang dikirim ke<br><strong>{{ $email }}</strong></p>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.verify-otp.submit') }}" id="otpForm" novalidate>
                        @csrf
                        
                        <input type="hidden" name="otp" id="otpHidden">

                        <div class="otp-inputs">
                            <input type="text" class="otp-input" maxlength="1" data-index="0" autofocus>
                            <input type="text" class="otp-input" maxlength="1" data-index="1">
                            <input type="text" class="otp-input" maxlength="1" data-index="2">
                            <input type="text" class="otp-input" maxlength="1" data-index="3">
                            <input type="text" class="otp-input" maxlength="1" data-index="4">
                            <input type="text" class="otp-input" maxlength="1" data-index="5">
                        </div>

                        @error('otp')
                            <div class="text-danger text-center mb-3">{{ $message }}</div>
                        @enderror

                        <div class="timer">
                            Kode berlaku selama <span id="countdown">15:00</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            <i class="bi bi-shield-check me-2"></i>Verifikasi
                        </button>

                        <div class="text-center mt-4">
                            <p class="text-muted">Tidak menerima kode?</p>
                            <form action="{{ route('password.email') }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit" class="resend-btn" id="resendBtn">
                                    Kirim Ulang OTP
                                </button>
                            </form>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="fw-semibold">
                                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 d-none d-lg-block">
                <div id="auth-right">
                    <img src="{{ asset('template/img/icon-login.png') }}" alt="OTP Verification" class="login-illustration">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');
        
        // OTP Input handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpHidden = document.getElementById('otpHidden');
        const otpForm = document.getElementById('otpForm');
        
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Only allow numbers
                e.target.value = value.replace(/[^0-9]/g, '');
                
                // Move to next input
                if (value && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Update hidden field
                updateOtpValue();
            });
            
            input.addEventListener('keydown', (e) => {
                // Handle backspace
                if (e.key === 'Backspace' && !e.target.value && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
            
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/[^0-9]/g, '').slice(0, 6);
                
                digits.split('').forEach((digit, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = digit;
                    }
                });
                
                updateOtpValue();
                
                if (digits.length >= 6) {
                    otpInputs[5].focus();
                }
            });
        });
        
        function updateOtpValue() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            otpHidden.value = otp;
        }
        
        // Countdown timer (15 minutes)
        let timeLeft = 15 * 60;
        const countdownEl = document.getElementById('countdown');
        
        const timer = setInterval(() => {
            timeLeft--;
            
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timer);
                countdownEl.textContent = 'Kadaluarsa';
                countdownEl.style.color = '#dc3545';
            }
        }, 1000);
    </script>
@endpush
