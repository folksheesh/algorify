@extends('layouts.template')

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content p-6">
            <h1 class="text-2xl font-semibold mb-4">Status Pembayaran</h1>

            <div style="max-width:980px;margin:0 auto;">
                <div style="background:#fff;border:1px solid #eef2f6;border-radius:12px;padding:28px;box-shadow:0 8px 24px rgba(15,23,42,0.04)">
                    <div style="text-align:center;margin-bottom:18px;">
                        @php
                            $status = $transaksi->status ?? 'pending';
                        @endphp
                        @if($status == 'pending')
                            <div style="width:72px;height:72px;border-radius:999px;background:linear-gradient(180deg,#fff8e1,#fff3c4);display:inline-flex;align-items:center;justify-content:center;margin:0 auto 12px auto;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 6V12L15 15" stroke="#D97706" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h2 style="margin:0;font-weight:700;color:#1e293b">Menunggu Pembayaran</h2>
                            <p style="margin:6px 0 0;color:#6b7280">Pembayaran Anda sedang dalam proses verifikasi</p>
                            <div style="display:inline-block;margin-top:8px;padding:4px 8px;background:#fff7ed;color:#b45309;border-radius:999px;font-size:12px;font-weight:600">Pending</div>

                        @elseif($status == 'success')
                            <div style="width:72px;height:72px;border-radius:999px;background:linear-gradient(180deg,#ecfdf5,#dcfce7);display:inline-flex;align-items:center;justify-content:center;margin:0 auto 12px auto;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 6L9 17l-5-5" stroke="#059669" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h2 style="margin:0;font-weight:700;color:#1e293b">Pembayaran Berhasil</h2>
                            <p style="margin:6px 0 0;color:#6b7280">Terima kasih — pembayaran Anda telah dikonfirmasi.</p>
                            <div style="display:inline-block;margin-top:8px;padding:4px 8px;background:#ecfdf5;color:#047857;border-radius:999px;font-size:12px;font-weight:600">Lunas</div>

                        @else
                            <div style="width:72px;height:72px;border-radius:999px;background:linear-gradient(180deg,#fff1f2,#fee2e2);display:inline-flex;align-items:center;justify-content:center;margin:0 auto 12px auto;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 9v4" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 17h.01" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <h2 style="margin:0;font-weight:700;color:#1e293b">Pembayaran Gagal</h2>
                            <p style="margin:6px 0 0;color:#6b7280">Mohon coba lagi — pembayaran tidak dapat diverifikasi.</p>
                            <div style="display:inline-block;margin-top:8px;padding:4px 8px;background:#fff1f2;color:#b91c1c;border-radius:999px;font-size:12px;font-weight:600">Gagal</div>
                        @endif
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 340px;gap:28px;align-items:start;">
                        <div>
                            <div style="display:flex;gap:18px;margin-bottom:18px;">
                                <div style="flex:1">
                                    <div style="background:#fafafa;border:1px solid #eef2f6;padding:12px;border-radius:8px;font-size:14px;color:#475569">
                                        <div style="display:flex;justify-content:space-between;margin-bottom:8px"><div class="text-sm text-gray-500">Nomor Transaksi</div><div>TRX{{ $transaksi->id }}</div></div>
                                        <div style="display:flex;justify-content:space-between;margin-bottom:8px"><div class="text-sm text-gray-500">Tanggal</div><div>{{ optional($transaksi->tanggal_transaksi)->format('d M Y, H:i') }}</div></div>
                                        <div style="display:flex;justify-content:space-between;margin-bottom:8px"><div class="text-sm text-gray-500">Metode Pembayaran</div><div>{{ $transaksi->metode_pembayaran }}</div></div>
                                        <div style="display:flex;justify-content:space-between;"><div class="text-sm text-gray-500">Nomor VA</div><div>1234567890</div></div>
                                    </div>
                                </div>
                                <div style="min-width:160px;text-align:right;">
                                    <div style="font-size:13px;color:#6b7280">Total Pembayaran</div>
                                    <div style="font-weight:700;font-size:18px;margin-top:6px">Rp {{ number_format($transaksi->nominal_pembayaran,0,',','.') }}</div>
                                </div>
                            </div>

                            <div style="background:#fafafa;border:1px solid #eef2f6;padding:12px;border-radius:8px;margin-bottom:18px;">
                                <div style="font-weight:600;margin-bottom:6px">Detail Pelatihan</div>
                                <div style="font-size:14px;color:#475569">Nama Pelatihan: {{ $transaksi->enrollment->kursus->judul ?? '-' }}</div>
                                <div style="font-size:14px;color:#475569">Peserta: {{ $transaksi->user->name ?? '-' }}</div>
                                <div style="font-size:14px;color:#475569">Email: {{ $transaksi->user->email ?? '-' }}</div>
                            </div>

                            <div style="margin-top:6px">
                                <div style="font-weight:600;margin-bottom:10px">Timeline Pembayaran</div>
                                <div style="border-left:2px solid #eef2f6;padding-left:14px;">
                                    {{-- Step 1: Pembayaran Diterima (always shown) --}}
                                    <div style="display:flex;gap:10px;margin-bottom:14px;">
                                        <div style="width:20px;height:20px;border-radius:6px;background:#eef2ff;display:flex;align-items:center;justify-content:center;color:#4c51bf;font-size:12px;flex-shrink:0">✓</div>
                                        <div>
                                            <div style="font-weight:600">Pembayaran Diterima</div>
                                            <div style="font-size:13px;color:#6b7280">{{ optional($transaksi->tanggal_transaksi)->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>

                                    {{-- Step 2: Verifikasi Pembayaran --}}
                                    <div style="display:flex;gap:10px;margin-bottom:14px;">
                                        @if($status == 'success')
                                            <div style="width:20px;height:20px;border-radius:6px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;color:#059669;font-size:12px;flex-shrink:0">✓</div>
                                            <div>
                                                <div style="font-weight:600">Verifikasi Pembayaran</div>
                                                <div style="font-size:13px;color:#6b7280">{{ optional($transaksi->tanggal_verifikasi)->format('d M Y, H:i') ?? 'Selesai' }}</div>
                                            </div>
                                        @elseif($status == 'pending')
                                            <div style="width:20px;height:20px;border-radius:6px;background:#fff8e1;display:flex;align-items:center;justify-content:center;color:#d97706;font-size:12px;flex-shrink:0">⟳</div>
                                            <div>
                                                <div style="font-weight:600">Verifikasi Pembayaran</div>
                                                <div style="font-size:13px;color:#6b7280">Sedang diproses</div>
                                            </div>
                                        @else
                                            <div style="width:20px;height:20px;border-radius:6px;background:#fff1f2;display:flex;align-items:center;justify-content:center;color:#dc2626;font-size:12px;flex-shrink:0">✕</div>
                                            <div>
                                                <div style="font-weight:600">Verifikasi Gagal</div>
                                                <div style="font-size:13px;color:#f43f5e">Pembayaran tidak dapat diverifikasi</div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Step 3: Akses Pelatihan Aktif --}}
                                    <div style="display:flex;gap:10px;margin-bottom:6px;">
                                        @if($status == 'success')
                                            <div style="width:20px;height:20px;border-radius:6px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;color:#059669;font-size:12px;flex-shrink:0">✓</div>
                                            <div>
                                                <div style="font-weight:600">Akses Pelatihan Aktif</div>
                                                <div style="font-size:13px;color:#6b7280">Dapat diakses sekarang</div>
                                            </div>
                                        @else
                                            <div style="width:20px;height:20px;border-radius:6px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:12px;flex-shrink:0">•</div>
                                            <div>
                                                <div style="font-weight:600">Akses Pelatihan Aktif</div>
                                                <div style="font-size:13px;color:#6b7280">Menunggu</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($status == 'failed' || $status == 'error')
                                <div style="margin-top:18px;background:#fff1f2;border:1px solid #fecaca;padding:14px;border-radius:8px;color:#991b1b">
                                    <div style="font-weight:700;margin-bottom:8px">Alasan Pembayaran Gagal</div>
                                    <ul style="margin:0 0 0 18px;padding:0;color:#7f1d1d">
                                        <li>Nomor Virtual Account tidak valid</li>
                                        <li>Waktu pembayaran telah kadaluarsa</li>
                                        <li>Nominal pembayaran tidak sesuai</li>
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div style="background:#fff;border:1px solid #eef2f6;padding:18px;border-radius:8px;">
                                <h4 style="margin:0 0 12px 0;font-weight:700">Ringkasan</h4>
                                <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#6b7280"><div>Nomor Transaksi</div><div>TRX{{ $transaksi->id }}</div></div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#6b7280"><div>Tanggal</div><div>{{ optional($transaksi->tanggal_transaksi)->format('d M Y, H:i') }}</div></div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#6b7280"><div>Metode</div><div>{{ $transaksi->metode_pembayaran }}</div></div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:8px;color:#6b7280"><div>Nomor VA</div><div>1234567890</div></div>
                                <div style="border-top:1px dashed #eef2f6;margin-top:12px;padding-top:12px;display:flex;justify-content:space-between;font-weight:700"><div>Total</div><div>Rp {{ number_format($transaksi->nominal_pembayaran,0,',','.') }}</div></div>
                            </div>

                            <div style="margin-top:12px;text-align:center">
                                @if($status == 'pending')
                                    <a href="#" style="display:inline-block;width:100%;background:linear-gradient(90deg,#6d28d9,#5b21b6);color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;text-decoration:none">Kembali ke Pembayaran</a>
                                    <form method="POST" action="{{ route('pembayaran.simulate', $transaksi->id) }}" style="margin-top:10px;">
                                        @csrf
                                        <button type="submit" style="width:100%;background:#fff;border:1px solid #e6e9ef;padding:10px;border-radius:10px;font-weight:600;color:#374151">Simulasikan Berhasil</button>
                                    </form>

                                    <div style="margin-top:12px;background:#eef2ff;border:1px solid #e0e7ff;padding:12px;border-radius:8px;color:#1e293b;font-size:13px;text-align:left">
                                        <strong>Catatan:</strong>
                                        <div style="margin-top:6px;color:#334155">Selesaikan pembayaran sebelum masa berlaku VA habis. Jika tidak menerima bukti pembayaran, hubungi layanan pelanggan.</div>
                                    </div>

                                @elseif($status == 'success')
                                    <a href="{{ route('pelatihan.index') }}" style="display:inline-block;width:100%;background:linear-gradient(90deg,#6d28d9,#5b21b6);color:#fff;padding:12px 16px;border-radius:10px;font-weight:700;text-decoration:none">Mulai Pelatihan</a>
                                @else
                                    <a href="{{ route('kursus.show', $transaksi->enrollment->kursus->id ?? 0) }}" style="display:inline-block;width:48%;background:linear-gradient(90deg,#6d28d9,#5b21b6);color:#fff;padding:10px 16px;border-radius:10px;font-weight:700;text-decoration:none">Coba Bayar Lagi</a>
                                    <a href="mailto:support@example.com" style="display:inline-block;width:48%;margin-left:4%;background:#fff;border:1px solid #fee2e2;padding:10px 16px;border-radius:10px;font-weight:700;color:#b91c1c;text-decoration:none">Hubungi CS</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
