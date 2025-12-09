@extends('layouts.template')

@section('title', 'Sertifikat Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1E293B;
            margin: 0 0 0.5rem 0;
        }

        .page-subtitle {
            color: #64748B;
            margin: 0;
            font-size: 0.95rem;
        }

        /* Certificate Card */
        .cert-card {
            background: white;
            border: 2px solid #C4B5FD;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 2rem;
            max-width: 380px;
        }

        .cert-header {
            background: linear-gradient(135deg, #EDE9FE 0%, #DDD6FE 100%);
            padding: 2rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cert-icon {
            width: 64px;
            height: 64px;
            color: #5D3FFF;
            display: block;
            margin-bottom: 1rem;
        }

        .cert-badge {
            display: inline-block;
            background: #5D3FFF;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 20px;
        }

        .cert-body {
            padding: 1.5rem;
        }

        .cert-course-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0 0 0.25rem 0;
        }

        .cert-instructor {
            color: #64748B;
            font-size: 0.9rem;
            margin: 0 0 1rem 0;
        }

        .cert-info-row {
            display: flex;
            gap: 0.35rem;
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
        }

        .cert-info-label {
            color: #64748B;
        }

        .cert-info-value {
            color: #1E293B;
            font-weight: 500;
        }

        .cert-info-value.highlight {
            color: #5D3FFF;
            font-weight: 600;
        }

        .cert-button-wrapper {
            text-align: center;
            margin-top: 1rem;
        }

        .btn-download-cert {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #5D3FFF;
            color: white;
            padding: 0.7rem 1.25rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-download-cert:hover {
            background: #4a2fcc;
            color: white;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #EDE9FE 0%, #E0E7FF 100%);
            border: 2px solid #C4B5FD;
            border-radius: 16px;
            padding: 1.5rem 2rem;
            max-width: 500px;
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .info-box-icon-wrapper {
            background: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-box-content {
            flex: 1;
        }

        .info-box-header {
            margin-bottom: 0.75rem;
        }

        .info-box-icon {
            width: 32px;
            height: 32px;
            color: #5D3FFF;
        }

        .info-box-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1E293B;
            margin: 0;
        }

        .info-box-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-box-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
            color: #64748B;
        }

        .info-box-item:last-child {
            margin-bottom: 0;
        }

        .info-box-item svg {
            width: 18px;
            height: 18px;
            color: #5D3FFF;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
            max-width: 400px;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #64748B;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .btn-explore {
            background: #5D3FFF;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .btn-explore:hover {
            background: #4a2fcc;
            color: white;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: modalSlide 0.3s ease;
        }

        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-icon {
            width: 64px;
            height: 64px;
            color: #5D3FFF;
            margin-bottom: 1rem;
        }

        .modal-title {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.75rem;
        }

        .modal-cert-number {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 0.85rem;
            color: #5D3FFF;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }

        .modal-text {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #64748B;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .modal-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .modal-btn {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 0.7rem 1.5rem;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
        }

        .modal-btn-cancel {
            background: #F1F5F9;
            color: #64748B;
        }

        .modal-btn-cancel:hover {
            background: #E2E8F0;
        }

        .modal-btn-confirm {
            background: #5D3FFF;
            color: white;
        }

        .modal-btn-confirm:hover {
            background: #4a2fcc;
            color: white;
        }

        /* Back Button */
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .back-button:hover {
            color: #5D3FFF;
        }

        .back-button svg {
            width: 20px;
            height: 20px;
        }

        /* Topbar Layout Adjustment */
        .dashboard-container.with-topbar {
            padding-top: 72px;
        }

        .dashboard-container.with-topbar .main-content {
            padding-top: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }

            .dashboard-container.with-topbar .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 80px 16px 60px 16px;
            }

            .cert-card, .info-box, .empty-state {
                max-width: 100%;
            }

            .back-button {
                position: fixed;
                top: 16px;
                left: 70px;
                z-index: 90;
                background: white;
                padding: 0.5rem 1rem;
                border-radius: 10px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                margin: 0;
            }
        }

        @media (max-width: 480px) {
            .main-content {
                padding: 70px 12px 80px 12px;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .cert-card {
                padding: 1.25rem;
            }

            .info-box {
                padding: 1.25rem;
            }
        }
    </style>
@endpush

@section('content')
    {{-- Topbar User --}}
    @include('components.topbar-user')

    <div class="dashboard-container with-topbar">
        @include('components.sidebar')
        <main class="main-content">
            <!-- Tombol Kembali -->
            <a href="{{ route('user.pelatihan-saya.index') }}" class="back-button">
                <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 16L6 10L12 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Kembali ke Pelatihan Saya
            </a>

            <div class="page-header">
                <h1 class="page-title">Sertifikat Saya</h1>
                <p class="page-subtitle">Dapatkan sertifikat untuk setiap pelatihan yang telah Anda selesaikan</p>
            </div>

            @if(session('success'))
                <div style="background: #D1FAE5; border: 1px solid #10B981; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; max-width: 400px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #FEE2E2; border: 1px solid #EF4444; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; max-width: 400px;">
                    {{ session('error') }}
                </div>
            @endif

            @if($completedEnrollments->count() > 0)
                @foreach($completedEnrollments as $enrollment)
                    <!-- Certificate Card -->
                    <div class="cert-card">
                        <!-- Header dengan background biru -->
                        <div class="cert-header">
                            <svg class="cert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M12 15l-2 5l2-1l2 1l-2-5z"/>
                                <circle cx="12" cy="9" r="6"/>
                            </svg>
                            @if($enrollment->has_certificate)
                                <span class="cert-badge">Sertifikat Tersedia</span>
                            @elseif($enrollment->progress >= 100 || $enrollment->nilai_akhir >= 70)
                                <span class="cert-badge" style="background: #F59E0B;">Siap Dibuat</span>
                            @else
                                <span class="cert-badge" style="background: #94A3B8;">Belum Selesai</span>
                            @endif
                        </div>

                        <!-- Body -->
                        <div class="cert-body">
                            <h3 class="cert-course-title">{{ $enrollment->kursus->judul }}</h3>
                            <p class="cert-instructor">Oleh {{ $enrollment->kursus->pengajar->name ?? 'Instruktur' }}</p>

                            @if($enrollment->has_certificate && $enrollment->certificate)
                                <div class="cert-info-row">
                                    <span class="cert-info-label">Tanggal Selesai:</span>
                                    <span class="cert-info-value">{{ \Carbon\Carbon::parse($enrollment->certificate->created_at)->locale('id')->translatedFormat('d F Y') }}</span>
                                </div>
                            @endif

                            <div class="cert-info-row">
                                <span class="cert-info-label">Nilai Akhir:</span>
                                <span class="cert-info-value highlight">{{ $enrollment->nilai_akhir ?? 0 }}/100</span>
                            </div>

                            <div class="cert-button-wrapper">
                                @if($enrollment->has_certificate)
                                    <button type="button" class="btn-download-cert" onclick="openDownloadModal('{{ $enrollment->certificate->id }}', '{{ $enrollment->kursus->judul }}', '{{ $enrollment->certificate->certificate_number ?? 'CERT-' . strtoupper(substr(md5($enrollment->certificate->id), 0, 8)) }}')">
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                        Download Sertifikat (PDF)
                                    </button>
                                @elseif($enrollment->progress >= 100 || $enrollment->nilai_akhir >= 70)
                                    <form action="{{ route('user.sertifikat.generate', $enrollment->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-download-cert">
                                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                            </svg>
                                            Dapatkan Sertifikat
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="btn-download-cert" style="background: #94A3B8; cursor: not-allowed;">
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Belum Dapat Sertifikat
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">📜</div>
                    <h2 class="empty-title">Belum Ada Pelatihan Selesai</h2>
                    <p class="empty-text">Selesaikan pelatihan untuk mendapatkan sertifikat</p>
                    <a href="{{ route('user.pelatihan-saya.index') }}" class="btn-explore">
                        Lihat Pelatihan Saya
                    </a>
                </div>
            @endif

            <!-- Info Box -->
            <div class="info-box">
                <div class="info-box-icon-wrapper">
                    <svg class="info-box-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M12 15l-2 5l2-1l2 1l-2-5z"/>
                        <circle cx="12" cy="9" r="6"/>
                    </svg>
                </div>
                <div class="info-box-content">
                    <div class="info-box-header">
                        <h4 class="info-box-title">Cara Mendapatkan Sertifikat</h4>
                    </div>
                    <ul class="info-box-list">
                        <li class="info-box-item">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Selesaikan semua modul dalam pelatihan (100%)</span>
                        </li>
                        <li class="info-box-item">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Lulus quiz final dengan nilai minimal 70</span>
                        </li>
                        <li class="info-box-item">
                            <svg viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Sertifikat dapat didownload dalam format PDF</span>
                        </li>
                    </ul>
                </div>
            </div>
        </main>
    </div>

    <!-- Download Modal -->
    <div class="modal-overlay" id="downloadModal">
        <div class="modal-content">
            <svg class="modal-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M12 15l-2 5l2-1l2 1l-2-5z"/>
                <circle cx="12" cy="9" r="6"/>
            </svg>
            <h3 class="modal-title">Download Sertifikat</h3>
            <p class="modal-cert-number" id="modalCertNumber"></p>
            <p class="modal-text">Apakah Anda ingin mengunduh sertifikat untuk kursus <strong id="modalCourseName"></strong>?</p>
            <div class="modal-buttons">
                <button class="modal-btn modal-btn-cancel" onclick="closeDownloadModal()">Batal</button>
                <a href="#" id="modalDownloadLink" class="modal-btn modal-btn-confirm">
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    @include('components.footer')
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        function openDownloadModal(certId, courseName, certNumber) {
            document.getElementById('modalCourseName').textContent = courseName;
            document.getElementById('modalCertNumber').textContent = 'No. Sertifikat: ' + certNumber;
            document.getElementById('modalDownloadLink').href = '/user/sertifikat/' + certId + '/download';
            document.getElementById('downloadModal').classList.add('active');
        }

        function closeDownloadModal() {
            document.getElementById('downloadModal').classList.remove('active');
        }

        // Close modal when clicking outside
        document.getElementById('downloadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDownloadModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDownloadModal();
            }
        });
    </script>
@endpush
