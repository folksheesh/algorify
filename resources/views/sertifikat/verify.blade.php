@extends('layouts.template')

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')

        <main class="main-content p-6">
            <div style="max-width:920px;margin:0 auto;">
                <div style="background:#fff;border-radius:12px;padding:28px;border:1px solid #eef2f6;box-shadow:0 12px 40px rgba(2,6,23,0.04)">
                    <h1 style="text-align:center;margin:0 0 6px 0;font-size:20px;font-weight:700;color:#0f172a">Verifikasi Sertifikat</h1>
                    <p style="text-align:center;margin:0 0 18px 0;color:#64748b">Masukkan nomor sertifikat untuk memverifikasi keasliannya</p>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('sertifikat.verify') }}">
                        @csrf
                        <div style="display:flex;gap:12px;align-items:center;margin-bottom:14px">
                            <input name="nomor" value="{{ old('nomor') ?? request('nomor') ?? '' }}" placeholder="Contoh: CERT-ALG-2025-001234" style="flex:1;border:1px solid #e6e9ef;padding:12px;border-radius:10px;">
                            <button type="submit" style="background:linear-gradient(90deg,#6d28d9,#5b21b6);color:#fff;padding:10px 16px;border-radius:10px;font-weight:700">Verifikasi</button>
                        </div>
                        <div style="color:#94a3b8;font-size:13px;margin-bottom:18px">Verifikasi dilakukan secara real-time dari database Algorify</div>
                    </form>

                    {{-- If no status yet, show instructions box --}}
                    @if(empty($status))
                        <div style="background:#fff;border:1px solid #eef2f6;padding:16px;border-radius:8px;">
                            <h3 style="margin:0 0 8px 0;font-weight:700">Cara Verifikasi Sertifikat</h3>
                            <ol style="margin:0;padding-left:18px;color:#475569">
                                <li class="mb-2">Dapatkan nomor sertifikat dari sertifikat fisik atau digital.</li>
                                <li class="mb-2">Masukkan nomor sertifikat pada kolom di atas.</li>
                                <li class="mb-2">Klik tombol "Verifikasi" atau tekan Enter.</li>
                                <li>Jika valid, sistem akan menampilkan detail sertifikat.</li>
                            </ol>
                            <p style="margin-top:8px;color:#9ca3af;font-size:12px">Catatan: Sertifikat yang valid akan menampilkan detail lengkap seperti nama penerima, pelatihan, tanggal penyelesaian, dan tanda tangan digital.</p>
                        </div>
                    @endif

                    {{-- Result: Valid --}}
                    @if(!empty($status) && $status == 'valid' && !empty($sertifikat))
                        <div style="margin-top:18px;border:2px solid #bbf7d0;background:#f0fdf4;border-radius:10px;padding:18px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px;">
                                <div>
                                    <h2 style="margin:0;font-weight:700;color:#0f172a">Sertifikat Valid</h2>
                                    <div style="margin-top:6px;color:#065f46;font-weight:600;background:#bbf7d0;padding:6px 8px;border-radius:6px;display:inline-block;font-size:13px">Terverifikasi</div>
                                </div>
                                <div style="text-align:right">
                                    <div style="font-size:13px;color:#6b7280">Nomor Sertifikat</div>
                                    <div style="font-weight:700">{{ $sertifikat->nomor_sertifikat }}</div>
                                </div>
                            </div>

                            <div style="display:grid;grid-template-columns:1fr 260px;gap:20px;align-items:start">
                                <div>
                                    <div style="background:#fff;border:1px solid #eef2f6;padding:12px;border-radius:8px;margin-bottom:12px">
                                        <div style="font-weight:700;margin-bottom:6px">Detail Penerima</div>
                                        <div style="font-size:14px;color:#0f172a">Nama Lengkap: {{ $sertifikat->user->name ?? '-' }}</div>
                                        <div style="font-size:14px;color:#475569">Pelatihan: {{ $sertifikat->kursus->judul ?? '-' }}</div>
                                        <div style="font-size:14px;color:#475569">Tanggal Selesai: {{ optional($sertifikat->tanggal_terbit)->format('d F Y') ?? '-' }}</div>
                                        <div style="font-size:14px;color:#475569">Nilai Akhir: <span style="font-weight:700;color:#059669">{{ $sertifikat->nilai_akhir ?? ($sertifikat->nilai ?? '-') }}</span></div>
                                    </div>

                                    <div style="background:#fff;border:1px solid #eef2f6;padding:12px;border-radius:8px">
                                        <div style="font-weight:700;margin-bottom:6px">Tertanda</div>
                                        <div style="font-size:14px;color:#475569">{{ config('app.name') }} — {{ $sertifikat->kursus->pengajar->name ?? 'Tim Pengajar' }}</div>
                                    </div>
                                </div>

                                <div>
                                    <div style="background:#fff;border:1px solid #eef2f6;padding:12px;border-radius:8px;margin-bottom:12px;text-align:center">
                                        {{-- Placeholder QR code --}}
                                        <div style="width:120px;height:120px;margin:0 auto 10px auto;background:#fff;border-radius:6px;display:flex;align-items:center;justify-content:center;border:1px solid #e6e9ef">
                                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="3" width="7" height="7" stroke="#0f172a" stroke-width="1.2"/><rect x="14" y="3" width="7" height="7" stroke="#0f172a" stroke-width="1.2"/><rect x="3" y="14" width="7" height="7" stroke="#0f172a" stroke-width="1.2"/></svg>
                                        </div>
                                        <div style="font-size:13px;color:#6b7280">QR Code Verifikasi</div>
                                        <div style="font-size:12px;color:#9ca3af;margin-top:8px">Scan untuk melihat detail sertifikat</div>
                                    </div>

                                    <a href="{{ url('storage/'.$sertifikat->file_path) }}" class="inline-block" style="display:inline-block;width:100%;background:linear-gradient(90deg,#6d28d9,#5b21b6);color:#fff;padding:10px 14px;border-radius:8px;text-align:center;font-weight:700;text-decoration:none">Download Bukti Verifikasi</a>
                                    <a href="{{ route('sertifikat.show', $sertifikat->id) }}" style="display:inline-block;margin-top:10px;width:100%;background:#fff;border:1px solid #e6e9ef;padding:10px 14px;border-radius:8px;text-align:center;font-weight:700;color:#374151;text-decoration:none">Kembali ke Sertifikat</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Result: Not found --}}
                    @if(!empty($status) && $status == 'notfound')
                        <div style="margin-top:18px;background:#fff1f2;border:1px solid #fee2e2;padding:18px;border-radius:10px">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px">
                                <div style="width:44px;height:44px;border-radius:10px;background:#fff;border:1px solid #fecaca;display:flex;align-items:center;justify-content:center;color:#b91c1c">!</div>
                                <div>
                                    <h2 style="margin:0;font-weight:700;color:#0f172a">Sertifikat Tidak Ditemukan</h2>
                                    <div style="color:#b91c1c;font-weight:600;margin-top:6px">Nomor sertifikat tidak terdaftar di database kami</div>
                                </div>
                            </div>

                            <div style="margin-top:8px;color:#92400e">
                                <strong>Kemungkinan Penyebab:</strong>
                                <ul style="margin-top:8px;margin-left:18px;color:#7f1d1d">
                                    <li>Nomor sertifikat salah atau tidak lengkap</li>
                                    <li>Sertifikat palsu atau tidak dikeluarkan oleh {{ config('app.name') }}</li>
                                    <li>Sertifikat telah dicabut atau tidak aktif</li>
                                </ul>
                            </div>
                            <div style="margin-top:12px">
                                <a href="{{ route('sertifikat.index') }}" style="display:inline-block;background:#fff;border:1px solid #e6e9ef;padding:10px 14px;border-radius:8px;text-decoration:none;color:#374151;font-weight:700">Kembali</a>
                            </div>
                        </div>
                    @endif

                    {{-- DB error notice --}}
                    @if(!empty($dbError) && $dbError)
                        <div style="margin-top:18px;background:#fff5f5;border:1px solid #fde2e2;padding:12px;border-radius:8px;color:#9b1c1c">
                            Terjadi masalah koneksi database. Verifikasi tidak dapat dilakukan saat ini.
                            <div style="margin-top:10px">
                                <a href="{{ route('sertifikat.index') }}" style="display:inline-block;background:#fff;border:1px solid #e6e9ef;padding:8px 12px;border-radius:8px;text-decoration:none;color:#374151;font-weight:700">Kembali</a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
@endsection
