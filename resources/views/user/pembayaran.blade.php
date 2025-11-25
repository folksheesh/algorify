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

                <hr class="divider">

                <h3 class="section-title">Metode Pembayaran</h3>
                <p class="subtitle">Pilih metode pembayaran melalui DOKU Payment Gateway</p>

                @if(isset($paymentUrl) && $paymentUrl)
                <a href="{{ $paymentUrl }}" class="btn-primary">
                    Bayar dengan DOKU
                </a>
                @else
                <button disabled class="btn-primary btn-disabled">
                    Link Pembayaran Tidak Tersedia
                </button>
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

<script>
    // Auto check payment status every 5 seconds
    @if($transaksi->status === 'pending')
    const checkInterval = setInterval(async () => {
        try {
            const response = await fetch('{{ route("user.transaksi.status", $transaksi->kode_transaksi) }}');
            const data = await response.json();
            
            if (data.status === 'success') {
                clearInterval(checkInterval);
                window.location.href = '{{ route("user.pelatihan-saya.index") }}?payment=success';
            } else if (data.status === 'failed' || data.status === 'expired') {
                clearInterval(checkInterval);
                location.reload();
            }
        } catch (error) {
            console.error('Error checking status:', error);
        }
    }, 5000);
    @endif
</script>
@endsection
