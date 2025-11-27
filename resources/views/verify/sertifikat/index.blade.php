@extends('layouts.template')

@section('title', 'Verifikasi Sertifikat - Algorify')

@push('styles')
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    {{-- Page-specific overrides: remove main's sidebar margin and center the verify card --}}
    <style>
        /* Remove any left/right offset the layout applies to `main` (sidebar margin) */
        @media (min-width: 1200px) {
            main { margin-left: 0 !important; margin-right: 0 !important; padding: 0 !important; }
        }

        /* Make the verification wrapper centered on the viewport */
        .verify-page { display:flex; align-items:center; justify-content:center; min-height:100vh; box-sizing:border-box; padding:3.5rem 1rem; }

        /* Constrain the card and keep it responsive */
        .verify-page .main-content { width:100%; max-width:960px; }
    </style>
@endpush

@section('content')
    <!-- standalone verification page - center content horizontally on the viewport -->
    <div class="verify-page" style="background:linear-gradient(180deg,#FBFBFF,#F7F8FF);">
        <main class="main-content">
            <div style="margin:0 auto;">
                <div style="text-align:center; margin-bottom:1.75rem;">
                    <img src="{{ asset('template/img/logo.png') }}" alt="Algorify" style="height:44px; margin-bottom:0.6rem;" />
                    <h1 style="font-size:2rem; margin:0.6rem 0 0.25rem;">Verifikasi Sertifikat</h1>
                    <p style="color:#6b7280; margin:0;">Masukkan nomor sertifikat untuk memverifikasi keasliannya</p>
                </div>

                <form method="POST" action="{{ route('verify.sertifikat.verify') }}">
                    @csrf
                    <div style="background:white; border-radius:12px; padding:1.25rem; box-shadow:0 6px 20px rgba(14,20,30,0.06); margin-bottom:1rem;">
                        <div style="display:flex; gap:0.75rem; align-items:center;">
                            <input name="nomor" placeholder="Contoh: CERT-ALG-2025-001234" value="{{ old('nomor', $query ?? '') }}" style="flex:1; padding:14px 16px; border-radius:8px; border:1px solid #E6EEF9; font-size:1rem;" />
                            <button type="submit" class="btn btn-primary" style="padding:0.6rem 1rem;">Verifikasi</button>
                        </div>
                        @if ($errors->has('nomor'))
                            <div style="color:#d32f2f; margin-top:0.5rem;">{{ $errors->first('nomor') }}</div>
                        @endif
                        <div style="display:flex; align-items:center; gap:0.5rem; color:#64748B; margin-top:0.75rem; font-size:0.9rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M12 2a10 10 0 100 20 10 10 0 000-20z" fill="#eef2ff" stroke="#667eea"/></svg>
                            <div>Verifikasi dilakukan secara real-time dari database Algorify</div>
                        </div>
                    </div>
                </form>

                {{-- Result area --}}
                <div>
                    @if(isset($result) && $result)
                        {{-- Enhanced 'valid certificate' card to match design provided by user --}}
                        @php
                            // try to load enrollment (nilai_akhir) if available
                            $enrollment = null;
                            try {
                                $enrollment = \App\Models\Enrollment::where('user_id', $result->user_id)
                                    ->where('kursus_id', $result->kursus_id)
                                    ->first();
                            } catch (\Exception $e) {
                                $enrollment = null;
                            }

                            // status label mapping
                            $statusMap = [
                                'active' => 'Aktif',
                                'revoked' => 'Dicabut',
                                'expired' => 'Kadaluarsa',
                            ];

                            $statusLabel = $statusMap[$result->status_sertifikat] ?? ucfirst($result->status_sertifikat ?? '—');

                            // QR URL pointing to public verification for this nomor
                            $qrUrl = route('verify.sertifikat.index', ['q' => $result->nomor_sertifikat]);
                        @endphp

                        <div style="background:white; border-radius:12px; padding:1.25rem; box-shadow:0 6px 28px rgba(16,24,40,0.08); margin-bottom:1rem; border:3px solid #10b981;">
                            <div style="display:flex; gap:1.25rem; align-items:flex-start;">
                                <div style="flex:0 0 72px; height:72px; display:flex; align-items:center; justify-content:center; border-radius:999px; background:#dcfce7;">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="#059669" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>

                                <div style="flex:1;">
                                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
                                        <div>
                                            <div style="font-weight:800; font-size:1.25rem; color:#0f172a;">Sertifikat Valid <span style="margin-left:0.6rem; display:inline-block; background:#ecfdf5; color:#065f46; font-weight:700; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.8rem;">Terverifikasi</span></div>
                                            <div style="color:#374151; font-size:0.95rem; margin-top:0.45rem;">Sertifikat ini terdaftar dan sah dari Algorify</div>
                                        </div>

                                        <div style="text-align:right; min-width:140px;">
                                            <div style="font-size:0.9rem; color:#6b7280;">Nomor</div>
                                            <div style="font-weight:700; margin-top:0.25rem; color:#0f172a;">{{ $result->nomor_sertifikat }}</div>
                                            <div style="margin-top:0.5rem; font-weight:700; color:#16a34a; display:flex; gap:0.5rem; align-items:center; justify-content:flex-end;">● <span style="font-weight:700; margin-left:6px;">{{ $statusLabel }}</span></div>
                                        </div>
                                    </div>

                                    <hr style="border:none; border-top:1px solid #eef2f7; margin:0.9rem 0;" />

                                    <div style="padding:0.5rem 0; color:#374151; font-weight:600;">Detail Penerima</div>

                                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.25rem; color:#475569; font-size:0.95rem;">
                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Nama Lengkap</div>
                                            <div style="font-weight:700; color:#0f172a; margin-top:0.35rem;">{{ $result->user->name ?? '—' }}</div>
                                        </div>

                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Pelatihan</div>
                                            <div style="font-weight:700; color:#0f172a; margin-top:0.35rem;">{{ $result->kursus->judul ?? '—' }}</div>
                                        </div>

                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Tanggal Selesai</div>
                                            <div style="font-weight:700; color:#0f172a; margin-top:0.35rem;">{{ optional($result->kursus->tanggal_selesai)->format('d F Y') ?? ($result->tanggal_terbit ? $result->tanggal_terbit->format('d F Y') : '-') }}</div>
                                        </div>

                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Nilai Akhir</div>
                                            <div style="font-weight:700; color:#4f46e5; margin-top:0.35rem;">{{ $enrollment && $enrollment->nilai_akhir ? (int)$enrollment->nilai_akhir . '/100' : '-' }}</div>
                                        </div>

                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Tanggal Terbit</div>
                                            <div style="font-weight:700; color:#0f172a; margin-top:0.35rem;">{{ $result->tanggal_terbit ? $result->tanggal_terbit->format('d F Y') : '-' }}</div>
                                        </div>

                                        <div>
                                            <div style="color:#9ca3af; font-size:0.85rem;">Ditandatangani</div>
                                            <div style="font-weight:700; color:#0f172a; margin-top:0.35rem;">{{ $result->signed_by ?? '—' }}</div>
                                        </div>
                                    </div>

                                    <hr style="border:none; border-top:1px solid #eef2f7; margin:1rem 0;" />

                                    <div style="display:flex; gap:1.25rem; align-items:flex-start;">
                                        <div style="flex:0 0 120px; background:#fff; border-radius:8px; padding:0.6rem; box-shadow:0 0 0 1px #eef2f7 inset; display:flex; align-items:center; justify-content:center;">
                                            <img src="https://chart.googleapis.com/chart?chs=160x160&cht=qr&chl={{ urlencode($qrUrl) }}" alt="QR Code" style="width:120px; height:120px; display:block;" />
                                        </div>

                                        <div style="flex:1; color:#475569;">
                                            <div style="font-weight:700; color:#0f172a;">QR Code Verifikasi</div>
                                            <div style="margin-top:0.4rem;">Scan QR code ini untuk memverifikasi sertifikat secara langsung</div>
                                        </div>
                                    </div>

                                    <div style="display:flex; gap:1rem; justify-content:space-between; margin-top:1rem;">
                                        <a href="{{ route('verify.sertifikat.index') }}" class="btn btn-outline" style="padding:0.7rem 1.25rem; border-radius:8px;">Verifikasi Sertifikat Lain</a>

                                        @if($result->file_path)
                                            <a href="{{ asset('storage/' . $result->file_path) }}" download class="btn btn-primary" style="padding:0.7rem 1.25rem; border-radius:8px;">⬇ Download Bukti Verifikasi</a>
                                        @else
                                            <button type="button" onclick="downloadVerificationProof({{ json_encode([ 'nomor' => $result->nomor_sertifikat, 'nama' => $result->user->name ?? '-', 'pelatihan' => $result->kursus->judul ?? '-' ]) }})" class="btn btn-primary" style="padding:0.7rem 1.25rem; border-radius:8px;">⬇ Download Bukti Verifikasi</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($query) && $query)
                        <div style="background:white; border-radius:12px; padding:1rem; box-shadow:0 6px 20px rgba(14,20,30,0.06); margin-bottom:1rem;">
                            <div style="font-weight:700; color:#EF4444;">Sertifikat tidak ditemukan</div>
                            <div style="color:#64748B; margin-top:0.5rem;">Nomor sertifikat yang kamu masukkan tidak valid atau belum terdaftar di database.</div>
                        </div>
                    @else
                        <div style="background:white; border-radius:12px; padding:1rem; box-shadow:0 6px 20px rgba(14,20,30,0.06); margin-bottom:1rem;">
                            <div style="font-weight:700; color:#0f172a;">Masukkan nomor sertifikat untuk memulai verifikasi</div>
                        </div>
                    @endif
                </div>

                {{-- How to verify box --}}
                <div style="background:white; border-radius:12px; padding:1rem; margin-top:1rem; box-shadow:0 6px 20px rgba(14,20,30,0.04);">
                    <div style="font-weight:700; color:#0f172a; margin-bottom:0.5rem;">Cara Verifikasi Sertifikat</div>
                    <ol style="color:#475569; padding-left:1.5rem; margin:0;">
                        <li>Dapatkan nomor sertifikat dari sertifikat fisik atau digital</li>
                        <li>Masukkan nomor sertifikat pada kolom di atas</li>
                        <li>Klik tombol "Verifikasi" atau tekan Enter</li>
                        <li>Sistem akan menampilkan status dan detail sertifikat</li>
                    </ol>
                    <p style="color:#9ca3af; margin-top:0.75rem; font-size:0.9rem;">Catatan: Sertifikat yang valid akan menampilkan detail lengkap seperti nama penerima, pelatihan, tanggal penyelesaian, dan tanda tangan digital.</p>
                </div>
            </div>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Small helper to download a simple JSON proof for demo purposes
        function downloadVerificationProof(data) {
            try {
                const payload = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
                const filename = (data && data.nomor) ? `bukti-verifikasi-${data.nomor}.json` : 'bukti-verifikasi.json';
                const blob = new Blob([payload], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                a.remove();
                URL.revokeObjectURL(url);
            } catch (e) {
                alert('Gagal menyiapkan file unduhan.');
            }
        }
    </script>
@endpush
