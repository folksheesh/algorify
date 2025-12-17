@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peserta/pembayaran.css') }}">
    

    {{-- Topbar User --}}
    @include('components.topbar-user')

    <div class="dashboard-container with-topbar">
        @include('components.sidebar')

        <!-- Main Content -->
        <main class="main-content">
            <!-- Tombol Kembali -->
            <a href="{{ route('kursus.show', $kursus->id) }}" class="back-button">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                <span class="hide-mobile">Kembali ke Detail Kursus</span>
                <span class="hide-desktop">Kembali</span>
            </a>

            <h1 class="page-title">Pembayaran</h1>

            @if (isset($transactionMessage) && $transactionMessage)
                @if ($transactionMessage['type'] === 'warning')
                    <div class="warning-box">
                        <p><strong>⚠️ Perhatian!</strong> {{ $transactionMessage['message'] }}</p>
                    </div>
                @elseif($transactionMessage['type'] === 'error')
                    <div class="error-box">
                        <p><strong>❌ Transaksi Gagal!</strong> {{ $transactionMessage['message'] }}</p>
                    </div>
                @endif
            @endif

            @if (isset($snapError) && $snapError)
                <div class="error-box">
                    <p><strong>Gagal mendapatkan link pembayaran DOKU.</strong> Detail: {{ $snapError }}</p>
                </div>
            @endif

            <div class="payment-grid">
                <!-- Left Column: Ringkasan Pembelian -->
                <div class="card">
                    <h2 class="card-title">Ringkasan Pembelian</h2>

                    {{-- @php
                        $thumbnailPath = $kursus->thumbnail ?? null;
                        $thumbnailUrl = null;
                        if ($thumbnailPath) {
                            $thumbnailUrl = \Illuminate\Support\Str::startsWith($thumbnailPath, ['http://', 'https://'])
                                ? $thumbnailPath
                                : (\Illuminate\Support\Str::startsWith($thumbnailPath, ['storage/', 'public/'])
                                    ? asset($thumbnailPath)
                                    : asset('storage/' . $thumbnailPath));
                        }
                    @endphp

                    <div class="course-media {{ $thumbnailUrl ? '' : 'no-image' }}">
                        @if ($thumbnailUrl)
                            <img class="course-media" src="{{ $thumbnailUrl }}" alt="Poster {{ $kursus->judul }}">
                        @else
                            <div class="course-media-placeholder">
                                <span>{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($kursus->judul, 0, 1)) }}</span>
                                <p>Tidak ada gambar kursus</p>
                            </div>
                        @endif
                    </div> --}}

                    <div class="summary-row">
                        <span class="summary-label">Nama Kursus:</span>
                        <span class="summary-value">{{ $kursus->judul }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Pengajar:</span>
                        <span class="summary-value">{{ $kursus->pengajar->name ?? 'N/A' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Harga Kursus:</span>
                        <span class="summary-value">Rp {{ number_format($kursus->harga, 0, ',', '.') }}</span>
                    </div>

                    <hr class="divider">

                    <div class="summary-row">
                        <span class="total-label">Total Bayar:</span>
                        <span class="total-value">Rp {{ number_format($kursus->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="info-note">
                        <p><strong>Catatan:</strong> Setelah pembayaran berhasil, Anda akan otomatis terdaftar di kursus ini
                            dan dapat langsung mengakses semua materi pembelajaran.</p>
                    </div>
                </div>

                <!-- Right Column: Metode Pembayaran -->
                <div class="card">
                    <h2 class="card-title">Detail Transaksi</h2>

                    <div class="instructions">
                        <div class="summary-row">
                            <span class="summary-label">Kode Transaksi:</span>
                            <span class="summary-value">{{ $transaksi->kode_transaksi }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Status:</span>
                            <span class="summary-value" id="payment-status">
                                @if ($transaksi->status === 'pending')
                                    <span style="color: #f59e0b;">Menunggu Pembayaran</span>
                                @elseif($transaksi->status === 'success')
                                    <span style="color: #10b981;">✓ Berhasil</span>
                                @elseif($transaksi->status === 'failed')
                                    <span style="color: #ef4444;">✗ Gagal</span>
                                @elseif($transaksi->status === 'expired')
                                    <span style="color: #6b7280;">⌛ Kadaluarsa</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <hr class="divider">

                    <h3 class="section-title">Metode Pembayaran</h3>
                    <p class="subtitle">Pilih metode pembayaran melalui DOKU Payment Gateway</p>

                    @if (isset($paymentUrl) && $paymentUrl)
                        <button id="pay-button" data-url="{{ $paymentUrl }}" class="btn-primary">
                            Bayar dengan DOKU
                        </button>
                    @else
                        <button disabled class="btn-primary btn-disabled">
                            Link Pembayaran Tidak Tersedia
                        </button>
                    @endif

                    @if (in_array($transaksi->status, ['failed', 'expired']))
                        <form action="{{ route('user.kursus.pembayaran', $kursus->id) }}" method="GET"
                            style="margin-top: 15px;">
                            <input type="hidden" name="new" value="1">
                            <button type="submit" class="btn-primary" style="background: #10b981;">
                                🔄 Buat Transaksi Baru
                            </button>
                        </form>
                    @endif

                </div>
            </div>

            <div class="card instruction-card">
                <div class="instruction-header">
                    <div>
                        <p class="instruction-label">Langkah Pembayaran</p>
                        <h3>Ikuti panduan ini untuk menyelesaikan transaksi</h3>
                    </div>
                    <span class="instruction-status">Gateway: DOKU</span>
                </div>
                <div class="instruction-steps">
                    <div class="instruction-step-card">
                        <div class="step-number">1</div>
                        <div>
                            <p class="step-title">Mulai Pembayaran</p>
                            <p class="step-desc">Tekan tombol <strong>Bayar dengan DOKU</strong> lalu tunggu jendela pembayaran muncul.</p>
                        </div>
                    </div>
                    <div class="instruction-step-card">
                        <div class="step-number">2</div>
                        <div>
                            <p class="step-title">Pilih Metode</p>
                            <p class="step-desc">Di halaman DOKU, pilih metode yang diinginkan seperti Virtual Account, E-Wallet, atau Kartu Kredit.</p>
                        </div>
                    </div>
                    <div class="instruction-step-card">
                        <div class="step-number">3</div>
                        <div>
                            <p class="step-title">Selesaikan Pembayaran</p>
                            <p class="step-desc">Ikuti instruksi pada kanal yang dipilih hingga transaksi dinyatakan berhasil.</p>
                        </div>
                    </div>
                    <div class="instruction-step-card">
                        <div class="step-number">4</div>
                        <div>
                            <p class="step-title">Akses Kursus</p>
                            <p class="step-desc">Setelah status berubah menjadi berhasil, Anda otomatis terdaftar dan bisa langsung belajar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    @include('components.footer')

    <!-- DOKU Payment Popup Modal -->
    <div id="doku-modal" class="doku-modal-container">
        <div class="doku-modal-content">
            <!-- Header Bar -->
            <div class="doku-modal-header">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="doku-header-icon">
                        <svg style="width: 18px; height: 18px; color: #5D3FFF;" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                            <path fill-rule="evenodd"
                                d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="doku-header-title">DOKU Payment Gateway</h3>
                        <p class="doku-header-subtitle">Secure Payment · SSL Encrypted</p>
                    </div>
                </div>
                <button id="close-modal" class="doku-close-btn">
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="doku-loading-state">
                <div style="text-align: center;">
                    <div class="doku-spinner">
                        <div class="spinner-track"></div>
                        <div class="spinner-fill"></div>
                    </div>
                    <h4 class="loading-title">Memuat Halaman Pembayaran</h4>
                    <p class="loading-subtitle">Mohon tunggu sebentar...</p>
                    <div class="loading-dots">
                        <div class="dot"></div>
                        <div class="dot"></div>
                        <div class="dot"></div>
                    </div>
                </div>
            </div>

            <!-- Iframe -->
            <iframe id="doku-iframe" class="doku-iframe"></iframe>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initDokuPayment();
            initPaymentStatusCheck();
        });

        function initDokuPayment() {
            const payButton = document.getElementById('pay-button');
            const modal = document.getElementById('doku-modal');
            const iframe = document.getElementById('doku-iframe');
            const loadingState = document.getElementById('loading-state');
            const closeButton = document.getElementById('close-modal');

            if (!payButton || !modal) return;

            const paymentUrl = payButton.getAttribute('data-url');
            if (!paymentUrl) return;

            payButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                modal.style.display = 'flex';
                loadingState.style.display = 'flex';
                iframe.style.display = 'none';
                iframe.src = paymentUrl;
            });

            iframe.addEventListener('load', function() {
                loadingState.style.display = 'none';
                iframe.style.display = 'block';
            });

            closeButton.addEventListener('click', function() {
                closeModal();
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeModal();
                }
            });

            function closeModal() {
                modal.style.display = 'none';
                iframe.src = '';
            }
        }

        function initPaymentStatusCheck() {
            const statusElement = document.getElementById('payment-status');
            const statusUrl = "{{ route('user.transaksi.status', $transaksi->kode_transaksi) }}";
            const successUrl = "{{ route('user.pelatihan-saya.index') }}";

            let checkCount = 0;
            const maxChecks = 300;

            const checkInterval = setInterval(async () => {
                checkCount++;

                try {
                    const response = await fetch(statusUrl);
                    const data = await response.json();

                    console.log('Payment check #' + checkCount + ':', data.status);

                    updateStatusDisplay(data.status, statusElement);

                    if (data.status === 'success') {
                        clearInterval(checkInterval);
                        window.location.href = successUrl + '?payment=success';
                    } else if (data.status === 'failed' || data.status === 'expired') {
                        clearInterval(checkInterval);
                        location.reload();
                    }

                    if (checkCount >= maxChecks) {
                        clearInterval(checkInterval);
                        console.log('Max check attempts reached');
                    }
                } catch (error) {
                    console.error('Error checking status:', error);
                }
            }, 2000);
        }

        function updateStatusDisplay(status, element) {
            const statusMap = {
                'pending': '<span style="color: #f59e0b;">Menunggu Pembayaran</span>',
                'success': '<span style="color: #10b981;">✓ Berhasil</span>',
                'failed': '<span style="color: #ef4444;">✗ Gagal</span>',
                'expired': '<span style="color: #6b7280;">⌛ Kadaluarsa</span>'
            };

            if (element && statusMap[status]) {
                element.innerHTML = statusMap[status];
                console.log('Status updated to:', status);
            }
        }
    </script>
@endsection
