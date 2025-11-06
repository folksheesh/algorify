@extends('layouts.template')

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content p-6">
            <h1 class="text-2xl font-semibold mb-6">Pembayaran Pelatihan</h1>

            <div style="max-width:1100px;margin:0 auto;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px;">
                    <!-- Left: Payment card -->
                    <div>
                        <div style="background:#fff;border:1px solid #eef2f6;border-radius:12px;padding:24px;box-shadow:0 8px 24px rgba(15,23,42,0.04);">
                            <div style="font-size:20px;font-weight:700;margin-bottom:8px;">{{ $kursus->judul }}</div>
                            <div style="color:#6b7280;margin-bottom:18px;">{{ $kursus->deskripsi_singkat ?? '' }}</div>

                            <div style="display:flex;gap:16px;align-items:center;margin-bottom:12px;">
                                <div style="flex:1">
                                    <div style="font-size:13px;color:#6b7280">Metode Pembayaran</div>
                                    <select name="metode_pembayaran" form="daftar-form" style="margin-top:8px;padding:10px;border:1px solid #e5e7eb;border-radius:8px;width:100%">
                                        <option value="VA">Mandiri Virtual Account</option>
                                        <option value="TRF">Transfer Bank</option>
                                    </select>
                                </div>
                                <div style="min-width:160px;text-align:right">
                                    <div style="font-size:13px;color:#6b7280">Harga Pelatihan</div>
                                    <div style="font-weight:700;font-size:18px;margin-top:8px">Rp {{ number_format($kursus->harga,0,',','.') }}</div>
                                </div>
                            </div>

                            <div style="border-top:1px dashed #eef2f6;margin-top:18px;padding-top:18px;display:flex;gap:12px;align-items:center;justify-content:flex-start;">
                                <form id="daftar-form" method="POST" action="{{ route('kursus.daftar.store', $kursus->id) }}">
                                    @csrf
                                    <button type="submit" style="background:#5D3FFF;color:#fff;padding:12px 22px;border-radius:10px;border:none;font-weight:600;">Cek Status Pembayaran</button>
                                </form>
                                <a href="{{ route('kursus.index') }}" style="display:inline-block;padding:12px 18px;border-radius:10px;border:1px solid #e5e7eb;color:#6b7280;text-decoration:none">Kembali</a>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Payment terms / customer details -->
                    <div>
                        <div style="background:#fff;border:1px solid #eef2f6;border-radius:12px;padding:24px;box-shadow:0 8px 24px rgba(15,23,42,0.02);">
                            <h3 style="margin:0 0 12px 0;font-weight:700">Ketentuan Pembayaran</h3>
                            <ol style="color:#475569;padding-left:18px;margin:0 0 12px 0;">
                                <li style="margin-bottom:8px">Lakukan pembayaran sebelum pelatihan dimulai.</li>
                                <li style="margin-bottom:8px">Pembayaran hanya dianggap sah jika dilakukan melalui nomor VA yang ditampilkan di sistem.</li>
                                <li style="margin-bottom:8px">Akses pelatihan aktif setelah status pembayaran "Berhasil".</li>
                                <li>Biaya pelatihan yang sudah dibayarkan tidak dapat dikembalikan.</li>
                            </ol>

                            <div style="margin-top:18px;padding:12px;background:#f9fafb;border-radius:8px;border:1px solid #eef2f6;color:#475569">
                                <div style="font-size:13px;color:#6b7280;margin-bottom:8px">Detail Langganan</div>
                                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px"><span>Nama</span><span>{{ auth()->user()->name }}</span></div>
                                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px"><span>Email</span><span>{{ auth()->user()->email }}</span></div>
                                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px"><span>Pelatihan</span><span>{{ $kursus->judul }}</span></div>
                                <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;margin-top:8px"><span>Harga Pelatihan</span><span>Rp {{ number_format($kursus->harga,0,',','.') }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
