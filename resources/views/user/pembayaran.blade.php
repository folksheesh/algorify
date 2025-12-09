@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
<style>
/* Pembayaran Page Styles */
* { box-sizing: border-box; }
body { margin: 0; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f8f9fa; }
.dashboard-container { display: flex; min-height: 100vh; }
.main-content { flex: 1; padding: 30px; margin-left: 280px; }
.page-title { font-size: 24px; margin-bottom: 30px; }
.payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 1200px; }
.card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
.card-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; flex-wrap: wrap; gap: 8px; }
.summary-label { color: #666; }
.summary-value { font-weight: 600; word-break: break-word; }
.divider { border: none; border-top: 1px solid #e0e0e0; margin: 20px 0; }
.total-label { font-weight: bold; }
.total-value { color: #3A6DFF; font-weight: bold; }
.section-title { font-size: 16px; font-weight: 600; margin-bottom: 10px; }
.subtitle { color: #666; font-size: 14px; margin-bottom: 15px; }
.info-note { background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-top: 20px; }
.info-note p { color: #0369a1; font-size: 14px; margin: 0; }
.btn-primary { background: #3A6DFF; color: #fff; border: none; padding: 15px 30px; width: 100%; max-width: 100%; margin: 0 auto; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: 600; text-decoration: none; display: block; text-align: center; }
.btn-primary:hover { background: #4a2fcc; color: #fff; }
.btn-disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }

/* Error notification */
.error-box { background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
.error-box p { color: #991b1b; font-size: 14px; margin: 0; }
.error-box strong { font-weight: 700; }

/* Warning notification */
.warning-box { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
.warning-box p { color: #92400e; font-size: 14px; margin: 0; }
.warning-box strong { font-weight: 700; }

/* Info notification */
.info-box { background: #dbeafe; border: 1px solid #3b82f6; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
.info-box p { color: #1e40af; font-size: 14px; margin: 0; }
.info-box strong { font-weight: 700; }

.instructions { margin-bottom: 20px; }
.instructions h6 { font-weight: 600; margin-bottom: 10px; }
.instructions ul { list-style: none; padding-left: 15px; }
.instructions li { color: #666; font-size: 14px; margin-bottom: 8px; }

/* Topbar Layout Adjustment */
.dashboard-container.with-topbar {
    padding-top: 72px;
}

.dashboard-container.with-topbar .main-content {
    padding-top: 1.5rem;
}

/* Responsive untuk tablet */
@media (max-width: 992px) {
    .main-content { margin-left: 0; padding: 80px 24px 24px 24px; }
    .payment-grid { grid-template-columns: 1fr; gap: 20px; }
    .dashboard-container.with-topbar .main-content { margin-left: 0; }
}

/* Responsive untuk mobile */
@media (max-width: 768px) {
    .dashboard-container { flex-direction: column; }
    .main-content { margin-left: 0; padding: 80px 16px 40px 16px; }
    .payment-grid { grid-template-columns: 1fr; gap: 16px; }
    .page-title { font-size: 20px; margin-bottom: 20px; }
    .card { padding: 20px; border-radius: 10px; }
    .card-title { font-size: 16px; }
    .summary-row { flex-direction: column; gap: 4px; }
    .btn-primary { padding: 12px 24px; font-size: 15px; max-width: 100%; }
}

@media (max-width: 480px) {
    .main-content { padding: 70px 12px 40px 12px; }
    .page-title { font-size: 18px; }
    .card { padding: 16px; }
    .card-title { font-size: 15px; }
    .section-title { font-size: 14px; }
    .subtitle { font-size: 13px; }
    .btn-primary { padding: 10px 20px; font-size: 14px; max-width: 100%; }
}

/* Back Button Styling */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    margin-bottom: 1rem;
    transition: color 0.2s;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 0.5rem 0;
}

.back-button:hover {
    color: #5D3FFF;
}

.back-button svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.hide-mobile { display: inline; }
.hide-desktop { display: none; }

@media (max-width: 768px) {
    .back-button {
        position: fixed;
        top: 16px;
        left: 70px;
        z-index: 90;
        background: white;
        padding: 0.625rem 1rem;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin: 0;
    }
    
    .hide-mobile { display: none !important; }
    .hide-desktop { display: inline !important; }
}

@media (max-width: 480px) {
    .back-button {
        top: 14px;
        left: 65px;
        padding: 0.5rem 0.875rem;
        font-size: 0.8rem;
    }
    
    .back-button svg {
        width: 16px;
        height: 16px;
    }
}

/* DOKU Modal Styles */
.doku-modal-container {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(93, 63, 255, 0.15), rgba(16, 185, 129, 0.15));
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}

.doku-modal-content {
    background: white;
    border-radius: 20px;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
    width: 92%;
    max-width: 1100px;
    height: 88vh;
    max-height: 850px;
    position: relative;
    margin: auto;
    overflow: hidden;
    border: 2px solid rgba(93, 63, 255, 0.1);
}

.doku-modal-header {
    background: linear-gradient(135deg, #3A6DFF, #3A6DFF);
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.doku-header-icon {
    width: 32px;
    height: 32px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.doku-header-title {
    margin: 0;
    color: white;
    font-size: 16px;
    font-weight: 600;
}

.doku-header-subtitle {
    margin: 0;
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
}

.doku-close-btn {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.doku-close-btn:hover {
    background: rgba(255, 255, 255, 0.25);
}

.doku-loading-state {
    display: flex;
    align-items: center;
    justify-content: center;
    height: calc(100% - 66px);
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.doku-spinner {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto 24px;
}

.spinner-track {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 4px solid #e5e7eb;
    border-radius: 50%;
}

.spinner-fill {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 4px solid transparent;
    border-top-color: #3A6DFF;
    border-right-color: #3A6DFF;
    border-radius: 50%;
    animation: spin 1s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.loading-title {
    margin: 0 0 8px 0;
    color: #1f2937;
    font-size: 18px;
    font-weight: 600;
}

.loading-subtitle {
    margin: 0;
    color: #6b7280;
    font-size: 14px;
}

.loading-dots {
    display: flex;
    gap: 6px;
    justify-content: center;
    margin-top: 16px;
}

.loading-dots .dot {
    width: 8px;
    height: 8px;
    background: #3A6DFF;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}

.loading-dots .dot:nth-child(2) { animation-delay: 0.2s; }
.loading-dots .dot:nth-child(3) { animation-delay: 0.4s; }

.doku-iframe {
    width: 100%;
    height: calc(100% - 66px);
    border: none;
    display: none;
    background: white;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; transform: scale(0.8); }
    50% { opacity: 1; transform: scale(1.2); }
}

/* Mobile Fullscreen */
@media (max-width: 768px) {
    .doku-modal-container {
        background: rgba(0, 0, 0, 0.9);
        backdrop-filter: none;
    }
    
    .doku-modal-content {
        width: 100%;
        height: 100%;
        max-width: 100%;
        max-height: 100%;
        border-radius: 0;
        border: none;
    }
    
    .doku-modal-header {
        padding: 12px 16px;
    }
    
    .doku-header-title {
        font-size: 14px;
    }
    
    .doku-header-subtitle {
        font-size: 11px;
    }
    
    .doku-header-icon {
        width: 28px;
        height: 28px;
    }
    
    .doku-header-icon svg {
        width: 16px !important;
        height: 16px !important;
    }
    
    .doku-close-btn {
        width: 34px;
        height: 34px;
    }
    
    .doku-loading-state {
        height: calc(100% - 54px);
    }
    
    .doku-iframe {
        height: calc(100% - 54px);
    }
    
    .doku-spinner {
        width: 60px;
        height: 60px;
        margin-bottom: 20px;
    }
    
    .loading-title {
        font-size: 16px;
    }
    
    .loading-subtitle {
        font-size: 13px;
    }
}
</style>

{{-- Topbar User --}}
@include('components.topbar-user')

<div class="dashboard-container with-topbar">
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Tombol Kembali -->
        <a href="{{ route('kursus.show', $kursus->id) }}" class="back-button">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="hide-mobile">Kembali ke Detail Kursus</span>
            <span class="hide-desktop">Kembali</span>
        </a>

        <h1 class="page-title">Pembayaran</h1>

        @if(isset($transactionMessage) && $transactionMessage)
            @if($transactionMessage['type'] === 'warning')
            <div class="warning-box">
                <p><strong>⚠️ Perhatian!</strong> {{ $transactionMessage['message'] }}</p>
            </div>
            @elseif($transactionMessage['type'] === 'error')
            <div class="error-box">
                <p><strong>❌ Transaksi Gagal!</strong> {{ $transactionMessage['message'] }}</p>
            </div>
            @endif
        @endif

        @if(isset($snapError) && $snapError)
        <div class="error-box">
            <p><strong>Gagal mendapatkan link pembayaran DOKU.</strong> Detail: {{ $snapError }}</p>
        </div>
        @endif

        <div class="payment-grid">
            <!-- Left Column: Ringkasan Pembelian -->
            <div class="card">
                <h2 class="card-title">Ringkasan Pembelian</h2>

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
                    <p><strong>Catatan:</strong> Setelah pembayaran berhasil, Anda akan otomatis terdaftar di kursus ini dan dapat langsung mengakses semua materi pembelajaran.</p>
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
                            @if($transaksi->status === 'pending')
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

                @if(isset($paymentUrl) && $paymentUrl)
                <button id="pay-button" data-url="{{ $paymentUrl }}" class="btn-primary">
                    Bayar dengan DOKU
                </button>
                @else
                <button disabled class="btn-primary btn-disabled">
                    Link Pembayaran Tidak Tersedia
                </button>
                @endif

                @if(in_array($transaksi->status, ['failed', 'expired']))
                <form action="{{ route('user.kursus.pembayaran', $kursus->id) }}" method="GET" style="margin-top: 15px;">
                    <input type="hidden" name="new" value="1">
                    <button type="submit" class="btn-primary" style="background: #10b981;">
                        🔄 Buat Transaksi Baru
                    </button>
                </form>
                @endif

                <div class="instructions" style="margin-top: 20px;">
                    <h6>Instruksi Pembayaran:</h6>
                    <ul>
                        <li>1. Klik tombol "Bayar dengan DOKU"</li>
                        <li>2. Anda akan diarahkan ke halaman pembayaran DOKU</li>
                        <li>3. Pilih metode pembayaran (Virtual Account, E-Wallet, Credit Card, dll)</li>
                        <li>4. Selesaikan pembayaran sesuai instruksi</li>
                        <li>5. Setelah berhasil, Anda akan otomatis terdaftar di kursus</li>
                    </ul>
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
                    <svg style="width: 18px; height: 18px; color: #3A6DFF;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="doku-header-title">DOKU Payment Gateway</h3>
                    <p class="doku-header-subtitle">Secure Payment · SSL Encrypted</p>
                </div>
            </div>
            <button id="close-modal" class="doku-close-btn">
                <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
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
