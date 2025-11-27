@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
<style>
    * { box-sizing: border-box; }
    body { margin: 0; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif; background: #f8f9fa; }
    .dashboard-container { display: flex; min-height: 100vh; }
    .main-content { flex: 1; padding: 30px; margin-left: 280px; }
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
    .info-note { background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 8px; padding: 15px; margin-top: 20px; }
    .info-note p { color: #0369a1; font-size: 14px; margin: 0; }
    .btn-primary { background: #5D3FFF; color: #fff; border: none; padding: 15px; width: 100%; border-radius: 8px; font-size: 16px; cursor: pointer; font-weight: 600; text-decoration: none; display: block; text-align: center; }
    .btn-primary:hover { background: #4a2fcc; }
    .btn-disabled { background: #d1d5db; color: #6b7280; cursor: not-allowed; }
    
    /* Error notification */
    .error-box { background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .error-box p { color: #991b1b; font-size: 14px; margin: 0; }
    .error-box strong { font-weight: 700; }
    
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
    <main class="main-content">
        <h1 class="page-title">Pembayaran</h1>

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

                @if(isset($transaksi) && $transaksi)
                <div class="instructions">
                    <div class="summary-row">
                        <span class="summary-label">Kode Transaksi:</span>
                        <span class="summary-value">{{ $transaksi->kode_transaksi }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">Status:</span>
                        <span class="summary-value">
                            @if($transaksi->status === 'pending')
                                <span style="color: #f59e0b;">Menunggu Pembayaran</span>
                            @elseif($transaksi->status === 'success')
                                <span style="color: #10b981;">Berhasil</span>
                            @elseif($transaksi->status === 'failed')
                                <span style="color: #ef4444;">Gagal</span>
                            @elseif($transaksi->status === 'expired')
                                <span style="color: #6b7280;">Kadaluarsa</span>
                            @endif
                        </span>
                    </div>
                </div>
                @else
                <div class="instructions">
                    <p style="color: #6b7280; font-size: 14px;">Belum ada transaksi. Silakan pilih metode pembayaran dan klik "Proses Pembayaran".</p>
                </div>
                @endif

                <hr class="divider">

                <h3 class="section-title">Metode Pembayaran</h3>
                <p class="subtitle">Pilih metode pembayaran</p>

                @if(isset($paymentUrl) && $paymentUrl)
                    @if($paymentUrl === '#local-payment')
                    <!-- Local Payment Method -->
                    <div style="margin-bottom: 20px;">
                        <h4 style="color: #5D3FFF; margin-bottom: 15px;">Pilih Metode Pembayaran</h4>
                        
                        <div style="display: grid; gap: 10px;">
                            <!-- Bank Transfer -->
                            <div class="payment-method-box" data-method="bank_transfer" onclick="selectPayment('bank_transfer')">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2L2 7v3h20V7l-10-5zM4 17v3h16v-3H4zm0-2h16v-2H4v2z"/>
                                </svg>
                                <div>
                                    <strong>Transfer Bank</strong>
                                    <p style="margin: 0; font-size: 12px; color: #666;">BCA, BNI, Mandiri, BRI</p>
                                </div>
                            </div>
                            
                            <!-- E-Wallet -->
                            <div class="payment-method-box" data-method="e_wallet" onclick="selectPayment('e_wallet')">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                </svg>
                                <div>
                                    <strong>E-Wallet</strong>
                                    <p style="margin: 0; font-size: 12px; color: #666;">GoPay, OVO, Dana, ShopeePay</p>
                                </div>
                            </div>
                            
                            <!-- Credit Card -->
                            <div class="payment-method-box" data-method="credit_card" onclick="selectPayment('credit_card')">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                                </svg>
                                <div>
                                    <strong>Kartu Kredit</strong>
                                    <p style="margin: 0; font-size: 12px; color: #666;">Visa, Mastercard, JCB</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form sudah tidak perlu karena akan pakai AJAX -->
                    <input type="hidden" name="payment_method" id="selected_payment_method" value="">
                    <input type="hidden" id="has_pending_transaction" value="">
                    <button type="button" class="btn-primary" id="submit-payment-btn" disabled onclick="handlePaymentButton()">
                        <span id="btn-text">Proses Pembayaran</span>
                    </button>
                    
                    <p style="margin-top: 12px; padding: 12px; background: #fef3c7; border: 1px solid #5D3FFF; border-radius: 8px; color: #92400e; font-size: 14px;">
                        ℹ️ <strong>Mode Lokal:</strong> Simulasi pembayaran. Setelah klik "Proses Pembayaran", Anda punya waktu 10 menit untuk menyelesaikan pembayaran.
                    </p>
                    @else
                    <button id="pay-button" data-url="{{ $paymentUrl }}" class="btn-primary">
                        Bayar dengan DOKU
                    </button>
                    @endif
                @else
                <button disabled class="btn-primary btn-disabled">
                    Link Pembayaran Tidak Tersedia
                </button>
                @endif

                <div class="instructions" style="margin-top: 20px;">
                    <h6>Instruksi Pembayaran:</h6>
                    <ul>
                        <li>1. Klik tombol "Bayar"</li>
                        <li>2. Anda akan diarahkan ke halaman pembayaran</li>
                        <li>3. Pilih metode pembayaran (Virtual Account, E-Wallet, Credit Card, dll)</li>
                        <li>4. Selesaikan pembayaran sesuai instruksi</li>
                        <li>5. Setelah berhasil, Anda akan otomatis terdaftar di kursus</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- DOKU Payment Popup Modal -->
<div id="doku-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); width: 90%; max-width: 1000px; height: 85vh; max-height: 800px; position: relative; margin: auto;">
        <!-- Close Button -->
        <button id="close-modal" style="position: absolute; top: 16px; right: 16px; background: white; border: none; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
            <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Loading State -->
        <div id="loading-state" style="display: flex; align-items: center; justify-content: center; height: 100%;">
            <div style="text-align: center;">
                <div style="display: inline-block; width: 48px; height: 48px; border: 3px solid #e5e7eb; border-top-color: #3b82f6; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                <p style="margin-top: 16px; color: #6b7280; font-size: 14px;">Memuat halaman pembayaran...</p>
            </div>
        </div>
        
        <!-- Iframe -->
        <iframe id="doku-iframe" style="width: 100%; height: 100%; border: none; border-radius: 12px; display: none;"></iframe>
    </div>
</div>

<!-- Modal Pending Payment -->
<div id="pending-payment-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; overflow-y: auto; padding: 20px;">
    <div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 12px; padding: 30px; position: relative;">
        
        <!-- Tombol Close X -->
        <button 
            onclick="closePendingModal()" 
            style="position: absolute; top: 15px; right: 15px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 24px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;"
            onmouseover="this.style.background='#f3f4f6'; this.style.color='#1f2937'"
            onmouseout="this.style.background='none'; this.style.color='#6b7280'"
            title="Tutup">
            ×
        </button>
        
        <!-- Header Modal -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="width: 60px; height: 60px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <svg width="30" height="30" fill="#f59e0b" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
            </div>
            <h3 style="margin: 0 0 8px 0; color: #1f2937;">Menunggu Pembayaran</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Selesaikan pembayaran sebelum waktu habis</p>
        </div>

        <!-- Timer Countdown -->
        <div style="background: #fee2e2; border: 2px solid #fca5a5; border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: center;">
            <p style="margin: 0 0 5px 0; font-size: 12px; color: #991b1b;">Waktu Tersisa</p>
            <div id="countdown-timer" style="font-size: 32px; font-weight: bold; color: #dc2626;">10:00</div>
        </div>

        <!-- Detail Transaksi -->
        <div style="background: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 15px 0; color: #374151; font-size: 16px;">Detail Transaksi</h4>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Kode Transaksi</span>
                <strong id="modal-kode-transaksi" style="color: #1f2937;">-</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Kursus</span>
                <strong style="color: #1f2937;">{{ $kursus->judul }}</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Metode Pembayaran</span>
                <strong id="modal-payment-method" style="color: #1f2937;">-</strong>
            </div>
            
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 15px 0;">
            
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280; font-size: 16px;">Total Pembayaran</span>
                <strong style="color: #5D3FFF; font-size: 18px;">Rp {{ number_format($kursus->harga, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Instruksi Pembayaran -->
        <div style="background: #eff6ff; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
            <p style="margin: 0; font-size: 14px; color: #1e40af;">
                💡 <strong>Simulasi Pembayaran:</strong> Dalam mode development, klik tombol "Selesaikan Pembayaran" di bawah untuk mensimulasikan pembayaran yang berhasil.
            </p>
        </div>

        <!-- Action Button -->
        <button 
            type="button" 
            onclick="completePayment()" 
            style="width: 100%; padding: 15px; background: #5D3FFF; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;"
            onmouseover="this.style.background='#4c32cc'"
            onmouseout="this.style.background='#5D3FFF'">
            Selesaikan Pembayaran
        </button>
    </div>
</div>

<!-- Modal Invoice (Success) -->
<div id="invoice-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; overflow-y: auto; padding: 20px;">
    <div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 12px; padding: 30px; position: relative;">
        
        <!-- Tombol Close X -->
        <button 
            onclick="closeInvoiceModal()" 
            style="position: absolute; top: 15px; right: 15px; background: none; border: none; cursor: pointer; color: #6b7280; font-size: 24px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.2s;"
            onmouseover="this.style.background='#f3f4f6'; this.style.color='#1f2937'"
            onmouseout="this.style.background='none'; this.style.color='#6b7280'"
            title="Tutup">
            ×
        </button>
        
        <!-- Success Icon -->
        <div style="text-align: center; margin-bottom: 25px;">
            <div style="width: 80px; height: 80px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                <svg width="40" height="40" fill="#10b981" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
            </div>
            <h3 style="margin: 0 0 8px 0; color: #1f2937;">Pembayaran Berhasil!</h3>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Terima kasih, pembayaran Anda telah dikonfirmasi</p>
        </div>

        <!-- Invoice Details -->
        <div style="background: #f9fafb; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
            <h4 style="margin: 0 0 15px 0; color: #374151; font-size: 16px;">Invoice Pembayaran</h4>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Kode Transaksi</span>
                <strong id="invoice-kode-transaksi" style="color: #1f2937;">-</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Tanggal</span>
                <strong id="invoice-tanggal" style="color: #1f2937;">-</strong>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="color: #6b7280;">Kursus</span>
                <strong id="invoice-kursus" style="color: #1f2937;">-</strong>
            </div>
            
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 15px 0;">
            
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #6b7280; font-size: 16px;">Total Dibayar</span>
                <strong id="invoice-total" style="color: #10b981; font-size: 18px;">-</strong>
            </div>
        </div>

        <!-- Success Message -->
        <div style="background: #d1fae5; border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: center;">
            <p style="margin: 0; color: #065f46; font-size: 14px;">
                ✅ Enrollment berhasil! Anda sekarang dapat mengakses materi pelatihan.
            </p>
        </div>

        <!-- Action Button -->
        <a 
            href="{{ route('user.pelatihan-saya.index') }}"
            style="display: block; width: 100%; padding: 15px; background: #5D3FFF; color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; text-align: center; text-decoration: none; transition: background 0.2s;"
            onmouseover="this.style.background='#4c32cc'"
            onmouseout="this.style.background='#5D3FFF'">
            Lihat Pelatihan Saya
        </a>
    </div>
</div>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.payment-method-box {
    cursor: pointer;
    padding: 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.2s ease;
}

.payment-method-box:hover {
    border-color: #5D3FFF;
    background-color: #f9fafb;
}

.payment-method-box.selected {
    border-color: #5D3FFF;
    background-color: #f3f0ff;
}

.btn-primary {
    width: 100%;
    padding: 15px;
    background-color: #5D3FFF;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

.btn-primary:hover:not(:disabled) {
    background-color: #4c32cc;
}

.btn-primary:disabled {
    background-color: #9ca3af;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedMethod = '';
    let countdownInterval = null;
    let currentTransactionCode = null;
    let statusCheckInterval = null;
    
    // ========================================
    // FUNGSI: Pilih metode pembayaran
    // ========================================
    function selectPayment(method) {
        const boxes = document.querySelectorAll('.payment-method-box');
        const submitBtn = document.getElementById('submit-payment-btn');
        const hiddenInput = document.getElementById('selected_payment_method');
        
        // Jika sudah dipilih, klik lagi untuk deselect
        if (selectedMethod === method) {
            selectedMethod = '';
            hiddenInput.value = '';
            submitBtn.disabled = true;
            boxes.forEach(box => box.classList.remove('selected'));
        } else {
            // Pilih metode baru
            selectedMethod = method;
            hiddenInput.value = method;
            submitBtn.disabled = false;
            
            // Update visual
            boxes.forEach(box => {
                if (box.getAttribute('data-method') === method) {
                    box.classList.add('selected');
                } else {
                    box.classList.remove('selected');
                }
            });
        }
    }
    
    // Make selectPayment globally accessible
    window.selectPayment = selectPayment;
    
    // ========================================
    // FUNGSI: Tutup modal pending
    // ========================================
    window.closePendingModal = function() {
        document.getElementById('pending-payment-modal').style.display = 'none';
        // Timer tetap berjalan di background, tidak di-clear
    };
    
    // ========================================
    // FUNGSI: Tutup modal invoice
    // ========================================
    window.closeInvoiceModal = function() {
        document.getElementById('invoice-modal').style.display = 'none';
    };
    
    // ========================================
    // FUNGSI: Handle tombol pembayaran (bisa "Proses" atau "Lanjutkan")
    // ========================================
    window.handlePaymentButton = function() {
        const hasPending = document.getElementById('has_pending_transaction').value;
        
        // Jika ada transaksi pending, langsung buka modal
        if (hasPending === 'true') {
            openPendingModal();
        } else {
            // Jika belum ada, proses pembayaran baru
            processPayment();
        }
    };
    
    // ========================================
    // FUNGSI: Buka modal pending yang sudah ada
    // ========================================
    function openPendingModal() {
        // Cek localStorage atau backend data
        const savedTransaction = localStorage.getItem('pending_transaction');
        if (savedTransaction) {
            try {
                const txData = JSON.parse(savedTransaction);
                currentTransactionCode = txData.kode_transaksi;
                
                // Tampilkan modal dengan data yang ada
                const modalData = {
                    kode_transaksi: txData.kode_transaksi,
                    status: 'pending',
                    expired_at: txData.expired_at
                };
                
                // Set data ke modal
                document.getElementById('modal-kode-transaksi').textContent = txData.kode_transaksi;
                
                // Format payment method name
                const methodNames = {
                    'bank_transfer': 'Transfer Bank',
                    'e_wallet': 'E-Wallet',
                    'credit_card': 'Kartu Kredit'
                };
                document.getElementById('modal-payment-method').textContent = methodNames[txData.payment_method] || txData.payment_method;
                
                // TAMPILKAN MODAL
                document.getElementById('pending-payment-modal').style.display = 'block';
                
                // Timer dan status check sudah berjalan di background
            } catch (e) {
                console.error('Error parsing localStorage:', e);
            }
        }
    }
    
    // ========================================
    // FUNGSI: Proses pembayaran (create pending transaction)
    // ========================================
    window.processPayment = function() {
        const paymentMethod = document.getElementById('selected_payment_method').value;
        
        // Validasi metode pembayaran sudah dipilih
        if (!paymentMethod) {
            // Tidak gunakan alert, highlight payment method box
            const paymentSection = document.querySelector('.payment-method-box');
            if (paymentSection) {
                paymentSection.parentElement.scrollIntoView({ behavior: 'smooth' });
            }
            return;
        }
        
        // Disable button untuk prevent double click
        const submitBtn = document.getElementById('submit-payment-btn');
        const btnText = document.getElementById('btn-text');
        submitBtn.disabled = true;
        btnText.textContent = 'Memproses...';
        
        // Kirim request AJAX untuk create pending transaction
        fetch('{{ route("user.kursus.enroll", $kursus->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                payment_method: paymentMethod
            })
        })
        .then(response => {
            // Check jika response bukan OK
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Jika berhasil, tampilkan modal pending
            if (data.success) {
                // Jika ada redirect (sudah success sebelumnya)
                if (data.redirect) {
                    window.location.href = data.redirect;
                    return;
                }
                
                // Simpan kode transaksi
                currentTransactionCode = data.data.kode_transaksi;
                
                // Simpan data transaksi ke localStorage untuk persistence
                localStorage.setItem('pending_transaction', JSON.stringify({
                    kode_transaksi: data.data.kode_transaksi,
                    expired_at: data.data.expired_at,
                    payment_method: paymentMethod,
                    kursus_id: '{{ $kursus->id }}'
                }));
                
                // Set flag pending
                document.getElementById('has_pending_transaction').value = 'true';
                
                // Update button jadi "Lanjutkan Pembayaran"
                btnText.textContent = 'Lanjutkan Pembayaran';
                submitBtn.disabled = false;
                submitBtn.style.background = '#f59e0b';
                
                // Add hover effect untuk orange button
                submitBtn.onmouseover = function() { if (!this.disabled) this.style.background = '#d97706'; };
                submitBtn.onmouseout = function() { if (!this.disabled) this.style.background = '#f59e0b'; };
                
                // Disable payment method selection
                disablePaymentSelection();
                
                // Tampilkan modal pending payment
                showPendingModal(data.data, paymentMethod);
                
                // Start countdown timer
                startCountdown(data.data.expired_at);
                
                // Start status check interval
                startStatusCheck(data.data.kode_transaksi);
            } else {
                // Reset button jika error
                submitBtn.disabled = false;
                btnText.textContent = 'Proses Pembayaran';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.disabled = false;
            btnText.textContent = 'Proses Pembayaran';
        });
    };
    
    // ========================================
    // FUNGSI: Disable payment method selection
    // ========================================
    function disablePaymentSelection() {
        const boxes = document.querySelectorAll('.payment-method-box');
        boxes.forEach(box => {
            box.style.opacity = '0.5';
            box.style.pointerEvents = 'none';
            box.style.cursor = 'not-allowed';
        });
    }
    
    // ========================================
    // FUNGSI: Enable payment method selection
    // ========================================
    function enablePaymentSelection() {
        const boxes = document.querySelectorAll('.payment-method-box');
        boxes.forEach(box => {
            box.style.opacity = '1';
            box.style.pointerEvents = 'auto';
            box.style.cursor = 'pointer';
        });
    }
    
    // ========================================
    // FUNGSI: Tampilkan modal pending payment
    // ========================================
    function showPendingModal(transactionData, paymentMethod) {
        // Set data ke modal
        document.getElementById('modal-kode-transaksi').textContent = transactionData.kode_transaksi;
        
        // Format payment method name
        const methodNames = {
            'bank_transfer': 'Transfer Bank',
            'e_wallet': 'E-Wallet',
            'credit_card': 'Kartu Kredit'
        };
        document.getElementById('modal-payment-method').textContent = methodNames[paymentMethod] || paymentMethod;
        
        // Tampilkan modal
        document.getElementById('pending-payment-modal').style.display = 'block';
    }
    
    // ========================================
    // FUNGSI: Start countdown timer (10 menit)
    // ========================================
    function startCountdown(expiredAtISO) {
        // Clear existing interval jika ada
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        
        const expiredAt = new Date(expiredAtISO);
        const timerElement = document.getElementById('countdown-timer');
        
        // Update countdown setiap detik
        countdownInterval = setInterval(function() {
            const now = new Date();
            const diff = expiredAt - now;
            
            // Jika waktu habis
            if (diff <= 0) {
                clearInterval(countdownInterval);
                timerElement.textContent = '00:00';
                timerElement.style.color = '#dc2626';
                
                // Clear localStorage
                localStorage.removeItem('pending_transaction');
                
                // Stop status check
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
                
                // Enable payment method selection lagi
                enablePaymentSelection();
                
                // Tutup modal dan reload (otomatis kembali ke halaman awal)
                document.getElementById('pending-payment-modal').style.display = 'none';
                window.location.reload();
                return;
            }
            
            // Hitung menit dan detik
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            
            // Format dengan leading zero
            const formattedTime = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerElement.textContent = formattedTime;
            
            // Ubah warna jika kurang dari 1 menit
            if (minutes < 1) {
                timerElement.style.color = '#dc2626';
            }
        }, 1000);
    }
    
    // ========================================
    // FUNGSI: Start status check interval (cek expired dari backend)
    // ========================================
    function startStatusCheck(kodeTransaksi) {
        // Clear existing interval
        if (statusCheckInterval) {
            clearInterval(statusCheckInterval);
        }
        
        // Cek status setiap 5 detik
        statusCheckInterval = setInterval(function() {
            fetch(`/user/pembayaran/${kodeTransaksi}/status`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Jika status expired atau failed dari backend
                    if (data.status === 'expired' || data.status === 'failed') {
                        clearInterval(statusCheckInterval);
                        clearInterval(countdownInterval);
                        
                        // Clear localStorage
                        localStorage.removeItem('pending_transaction');
                        
                        // Tutup modal dan reload
                        document.getElementById('pending-payment-modal').style.display = 'none';
                        window.location.reload();
                    }
                    
                    // Jika status success (dibayar dari tempat lain)
                    if (data.status === 'success') {
                        clearInterval(statusCheckInterval);
                        clearInterval(countdownInterval);
                        
                        // Clear localStorage
                        localStorage.removeItem('pending_transaction');
                        
                        // Redirect ke pelatihan
                        window.location.href = '{{ route("user.pelatihan-saya.index") }}';
                    }
                }
            })
            .catch(error => {
                console.error('Status check error:', error);
            });
        }, 5000); // Check setiap 5 detik
    }
    
    // ========================================
    // FUNGSI: Complete payment (ubah status jadi success)
    // ========================================
    window.completePayment = function() {
        if (!currentTransactionCode) {
            return;
        }
        
        // Kirim request untuk complete payment
        fetch(`/user/pembayaran/${currentTransactionCode}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // Check jika response bukan OK
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Stop countdown timer
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
                
                // Stop status check
                if (statusCheckInterval) {
                    clearInterval(statusCheckInterval);
                }
                
                // Clear localStorage
                localStorage.removeItem('pending_transaction');
                
                // Tutup modal pending
                document.getElementById('pending-payment-modal').style.display = 'none';
                
                // Tampilkan invoice modal
                showInvoiceModal(data.data);
            } else {
                // Jika expired atau error, reload page
                if (data.status === 'expired') {
                    localStorage.removeItem('pending_transaction');
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    };
    
    // ========================================
    // FUNGSI: Tampilkan invoice modal (success)
    // ========================================
    function showInvoiceModal(invoiceData) {
        // Set data ke invoice modal
        document.getElementById('invoice-kode-transaksi').textContent = invoiceData.kode_transaksi;
        document.getElementById('invoice-tanggal').textContent = invoiceData.tanggal_pembayaran;
        document.getElementById('invoice-kursus').textContent = invoiceData.kursus.judul;
        document.getElementById('invoice-total').textContent = 'Rp ' + Number(invoiceData.kursus.harga).toLocaleString('id-ID');
        
        // Tampilkan modal invoice
        document.getElementById('invoice-modal').style.display = 'block';
    }
    
    // ========================================
    // CHECK: Restore pending transaction dari localStorage atau backend
    // TIDAK AUTO-SHOW MODAL, hanya ubah button text
    // ========================================
    
    // Cek localStorage terlebih dahulu (untuk persistence lintas page)
    const savedTransaction = localStorage.getItem('pending_transaction');
    if (savedTransaction) {
        try {
            const txData = JSON.parse(savedTransaction);
            const expiredAt = new Date(txData.expired_at);
            const now = new Date();
            
            // Jika belum expired, tandai ada pending transaction
            if (now < expiredAt && txData.kursus_id === '{{ $kursus->id }}') {
                currentTransactionCode = txData.kode_transaksi;
                
                // Set flag bahwa ada pending transaction
                document.getElementById('has_pending_transaction').value = 'true';
                
                // Ubah text button menjadi "Lanjutkan Pembayaran"
                const submitBtn = document.getElementById('submit-payment-btn');
                const btnText = document.getElementById('btn-text');
                btnText.textContent = 'Lanjutkan Pembayaran';
                submitBtn.disabled = false;
                submitBtn.style.background = '#f59e0b';
                
                // Add hover effect untuk orange button
                submitBtn.onmouseover = function() { if (!this.disabled) this.style.background = '#d97706'; };
                submitBtn.onmouseout = function() { if (!this.disabled) this.style.background = '#f59e0b'; };
                
                // Disable payment method selection
                disablePaymentSelection();
                
                // Start countdown di background (tidak tampilkan modal)
                startCountdown(txData.expired_at);
                
                // Start status check
                startStatusCheck(txData.kode_transaksi);
            } else {
                // Sudah expired, clear localStorage
                localStorage.removeItem('pending_transaction');
            }
        } catch (e) {
            console.error('Error parsing localStorage:', e);
            localStorage.removeItem('pending_transaction');
        }
    }
    // Jika tidak ada di localStorage, cek dari backend
    else {
        @if(isset($transaksi) && $transaksi && $transaksi->status === 'pending')
            // Tandai ada transaksi pending dari backend
            currentTransactionCode = '{{ $transaksi->kode_transaksi }}';
            
            // Hitung expired time (10 menit dari updated_at)
            const updatedAt = new Date('{{ $transaksi->updated_at->toIso8601String() }}');
            const expiredAt = new Date(updatedAt.getTime() + (10 * 60 * 1000));
            
            // Simpan ke localStorage untuk persistence
            localStorage.setItem('pending_transaction', JSON.stringify({
                kode_transaksi: '{{ $transaksi->kode_transaksi }}',
                expired_at: expiredAt.toISOString(),
                payment_method: '{{ $transaksi->metode_pembayaran }}',
                kursus_id: '{{ $kursus->id }}'
            }));
            
            // Set flag bahwa ada pending transaction
            document.getElementById('has_pending_transaction').value = 'true';
            
            // Ubah text button menjadi "Lanjutkan Pembayaran"
            const submitBtn = document.getElementById('submit-payment-btn');
            const btnText = document.getElementById('btn-text');
            btnText.textContent = 'Lanjutkan Pembayaran';
            submitBtn.disabled = false;
            submitBtn.style.background = '#f59e0b';
            
            // Add hover effect untuk orange button
            submitBtn.onmouseover = function() { if (!this.disabled) this.style.background = '#d97706'; };
            submitBtn.onmouseout = function() { if (!this.disabled) this.style.background = '#f59e0b'; };
            
            // Disable payment method selection
            disablePaymentSelection();
            
            // Start countdown di background
            startCountdown(expiredAt.toISOString());
            
            // Start status check
            startStatusCheck('{{ $transaksi->kode_transaksi }}');
        @endif
    }
    
    /* ===== DOKU INTEGRATION - COMMENTED FOR LATER =====
    
    @if(isset($paymentUrl) && $paymentUrl && $paymentUrl !== '#local-payment')
    const payButton = document.getElementById('pay-button');
    const modal = document.getElementById('doku-modal');
    const iframe = document.getElementById('doku-iframe');
    const loadingState = document.getElementById('loading-state');
    const closeButton = document.getElementById('close-modal');
    
    if (!payButton) return;
    
    const paymentUrl = payButton.getAttribute('data-url');
    if (!paymentUrl) return;
    
    // Open modal
    payButton.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        modal.style.display = 'flex';
        loadingState.style.display = 'flex';
        iframe.style.display = 'none';
        iframe.src = paymentUrl;
    });
    
    // Iframe loaded
    iframe.addEventListener('load', function() {
        loadingState.style.display = 'none';
        iframe.style.display = 'block';
    });
    
    // Close modal
    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
        iframe.src = '';
    });
    
    // Close on background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
            iframe.src = '';
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            modal.style.display = 'none';
            iframe.src = '';
        }
    });
    @endif
    
    // Auto check payment status every 5 seconds
    @if(isset($transaksi) && $transaksi && $transaksi->status === 'pending')
    const checkInterval = setInterval(async () => {
        try {
            const response = await fetch('{{ route("user.transaksi.status", $transaksi->kode_transaksi) }}');
            const data = await response.json();
            
            if (data.status === 'success') {
                clearInterval(checkInterval);
                modal.style.display = 'none';
                window.location.href = '{{ route("user.pelatihan-saya.index") }}?payment=success';
            } else if (data.status === 'failed' || data.status === 'expired') {
                clearInterval(checkInterval);
                modal.style.display = 'none';
                location.reload();
            }
        } catch (error) {
            console.error('Error checking status:', error);
        }
    }, 5000);
    @endif
    
    ===== END DOKU INTEGRATION ===== */
});
</script>
@endsection