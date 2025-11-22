@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f8f9fa; }
    .dashboard-container { display: flex; min-height: 100vh; }
    .main-content { flex: 1; padding: 30px; margin-left: 280px; }
    .page-title { font-size: 24px; margin-bottom: 30px; }
    .page-title { font-size: 24px; margin-bottom: 30px; }
    .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; max-width: 1200px; }
    .card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .card-title { font-size: 18px; font-weight: 600; margin-bottom: 20px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; }
    .summary-label { color: #666; }
    .summary-value { font-weight: 600; }
    .divider { border: none; border-top: 1px solid #e0e0e0; margin: 20px 0; }
    .total-label { font-weight: bold; }
    .total-value { color: #5D3FFF; font-weight: bold; }
    .section-title { font-size: 16px; font-weight: 600; margin-bottom: 10px; }
    .subtitle { color: #666; font-size: 14px; margin-bottom: 15px; }
    .va-box { background: #f8f9fa; border: 1px solid #e0e0e0; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
    .va-label { color: #666; font-size: 12px; margin-bottom: 5px; }
    .va-number { display: flex; justify-content: space-between; align-items: center; }
    .va-number h3 { margin: 0; font-size: 24px; }
    .copy-btn { background: #fff; border: 1px solid #ccc; padding: 8px 15px; border-radius: 6px; cursor: pointer; }
    .copy-btn:hover { background: #f0f0f0; }
    .info-note { background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-top: 20px; }
    .info-note p { color: #0369a1; font-size: 14px; margin: 0; }
    .btn-primary { background: #5D3FFF; color: #fff; border: none; padding: 15px; width: 100%; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: 600; }
    .btn-primary:hover { background: #4a2fcc; }
    
    /* Toast notification */
    .toast { position: fixed; bottom: 30px; right: 30px; background: #10b981; color: #fff; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); display: none; align-items: center; gap: 10px; z-index: 1000; animation: slideIn 0.3s ease; }
    .toast.show { display: flex; }
    .toast.error { background: #ef4444; }
    @keyframes slideIn { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    
    .countdown-card { border-left: 4px solid #5D3FFF; text-align: center; }
    .countdown-icon { font-size: 40px; color: #5D3FFF; margin-bottom: 15px; }
    .countdown-label { color: #666; margin-bottom: 10px; }
    .countdown-time { font-size: 32px; font-weight: bold; color: #5D3FFF; margin-bottom: 10px; }
    .countdown-deadline { color: #666; font-size: 14px; }
    .instructions { margin-bottom: 20px; }
    .instructions h6 { font-weight: 600; margin-bottom: 10px; }
    .instructions ul { list-style: none; padding-left: 15px; }
    .instructions li { color: #666; font-size: 14px; margin-bottom: 8px; }
    @media (max-width: 768px) {
        .dashboard-container { flex-direction: column; }
        .main-content { margin-left: 0; }
        .payment-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="dashboard-container">
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="page-title">Pembayaran Pelatihan</h1>

        <div class="payment-grid">
            <!-- Left Column -->
            <div>
                <div class="card">
                    <h2 class="card-title">Ringkasan Pesanan</h2>
                    
                    <div class="summary-row">
                        <span class="summary-label">Nama</span>
                        <span class="summary-value">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Email</span>
                        <span class="summary-value">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Pelatihan</span>
                        <span class="summary-value">{{ $kursus->judul }}</span>
                    </div>
                    
                    <hr class="divider">
                    
                    <div class="summary-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-value">RP {{ number_format($kursus->harga, 0, ',', '.') }}</span>
                    </div>

                    <h3 class="section-title" style="margin-top: 30px;">Mandiri Virtual Account</h3>
                    <p class="subtitle">Transfer ke nomor VA berikut</p>
                    
                    <div class="va-box">
                        <div class="va-label">Nomor Virtual Account</div>
                        <div class="va-number">
                            <h3>1234 5678 90</h3>
                            <button class="copy-btn" onclick="copyToClipboard('1234567890')">📋 Copy</button>
                        </div>
                    </div>

                    <div class="info-note">
                        <p>
                            ℹ️ <strong>Pembayaran Otomatis Terdeteksi</strong><br>
                            Setelah Anda melakukan transfer, sistem akan otomatis memverifikasi pembayaran Anda dan mengaktifkan akses pelatihan.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Countdown -->
                <div class="card countdown-card">
                    <div class="countdown-icon">⏰</div>
                    <div class="countdown-label">Selesaikan Pembayaran Dalam</div>
                    <div class="countdown-time" id="countdown">23:59:47</div>
                    <div class="countdown-deadline">Batas waktu: 5 November 2025 pukul 00:23</div>
                </div>

                <!-- Instructions -->
                <div class="card" style="margin-top: 20px;">
                    <h2 class="card-title">Cara Pembayaran</h2>
                    
                    <div class="instructions">
                        <h6>ATM Mandiri</h6>
                        <ul>
                            <li>• Pilih Bayar/Beli → Lainnya → Multipayment</li>
                            <li>• Masukkan kode 70012 dan nomor VA</li>
                            <li>• Konfirmasi pembayaran</li>
                        </ul>
                    </div>

                    <div class="instructions">
                        <h6>Mobile Banking</h6>
                        <ul>
                            <li>• Buka Livin' by Mandiri</li>
                            <li>• Pilih Bayar → Multipayment</li>
                            <li>• Masukkan nomor VA dan konfirmasi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="toast">
    <span id="toast-icon">✓</span>
    <span id="toast-message">Nomor VA berhasil disalin!</span>
</div>

<script>
// Countdown timer
let timeLeft = 86387; // 23:59:47 in seconds

function updateCountdown() {
    const hours = Math.floor(timeLeft / 3600);
    const minutes = Math.floor((timeLeft % 3600) / 60);
    const seconds = timeLeft % 60;
    
    document.getElementById('countdown').textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeLeft > 0) {
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    }
}

updateCountdown();

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('✓', 'Nomor VA berhasil disalin!', 'success');
    }).catch(() => {
        showToast('✕', 'Gagal menyalin nomor VA', 'error');
    });
}

function showToast(icon, message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toast-icon');
    const toastMessage = document.getElementById('toast-message');
    
    toastIcon.textContent = icon;
    toastMessage.textContent = message;
    toast.className = 'toast show ' + (type === 'error' ? 'error' : '');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
@endsection
