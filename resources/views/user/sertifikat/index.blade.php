@extends('layouts.template')

@section('title', 'Sertifikat Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg?v=' . time()) }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/peserta/sertifikat-index.css') }}">
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
                            <img class="cert-icon" src="{{ asset('template/img/medali-keren.png') }}" alt="Medal" style="width: 48px; height: 48px; object-fit: contain;" />
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
            <div class="info-box" style="margin-left: 0; margin-right: auto; max-width: 500px;">
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