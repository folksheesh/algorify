@extends('layouts.template')

@section('title', 'Sertifikat Saya - Algorify')

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <style>
        .cert-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .cert-card {
            background: white;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        .cert-icon-wrapper {
            background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%);
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        .cert-icon-svg {
            width: 28px;
            height: 28px;
            color: #5D3FFF;
        }
        .cert-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.35rem;
            text-align: center;
        }
        .cert-subtitle {
            color: #64748B;
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .cert-details {
            background: #F8FAFC;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
        .cert-detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #E2E8F0;
        }
        .cert-detail-row:last-child {
            border-bottom: none;
        }
        .cert-detail-label {
            color: #64748B;
            font-size: 0.9rem;
        }
        .cert-detail-value {
            color: #1E293B;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-download {
            background: #5D3FFF;
            color: white;
            border: none;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-download:hover {
            background: #4a2fcc;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(93, 63, 255, 0.3);
        }
        .info-box {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 12px;
            padding: 1.5rem;
        }
        .info-box-title {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: #1E40AF;
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        .info-box-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .info-box-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            color: #1E40AF;
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
            line-height: 1.5;
        }
        .info-box-list li:last-child {
            margin-bottom: 0;
        }
        .check-icon {
            width: 20px;
            height: 20px;
            background: #3B82F6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 16px;
        }
        .empty-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.5;
        }
        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1E293B;
            margin-bottom: 0.75rem;
        }
        .empty-text {
            color: #64748B;
            margin-bottom: 2rem;
            font-size: 1rem;
        }
        .btn-explore {
            background: #5D3FFF;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-explore:hover {
            background: #4a2fcc;
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        @include('components.sidebar')
        <main class="main-content">
            <h1 style="font-size: 1.75rem; font-weight: 700; margin-bottom: 0.5rem; color: #1E293B;">Sertifikat Saya</h1>
            <p style="color: #64748B; margin-bottom: 2rem;">Dapatkan sertifikat untuk setiap pelatihan yang telah Anda selesaikan</p>

            @if(session('success'))
                <div style="background: #D1FAE5; border: 1px solid #10B981; color: #065F46; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #FEE2E2; border: 1px solid #EF4444; color: #991B1B; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                    {{ session('error') }}
                </div>
            </section>
            <section class="stats-section">
                <div style="padding: 2rem; background: white; border-radius: 12px; margin-top: 2rem;">
                    <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #1E293B;">Halaman dalam pengembangan</h2>
                    <p style="color: #64748B;">Konten untuk halaman Sertifikat Saya akan segera ditambahkan.</p>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        document.documentElement.setAttribute('data-bs-theme', 'light');

        // Download Modal
        function showDownloadModal(courseName, certNumber, downloadUrl) {
            const modal = document.getElementById('download-modal');
            document.getElementById('modal-course-name').textContent = courseName;
            document.getElementById('modal-cert-number').textContent = certNumber;
            document.getElementById('modal-download-link').href = downloadUrl;
            modal.style.display = 'flex';
        }

        function closeDownloadModal() {
            document.getElementById('download-modal').style.display = 'none';
        }

        // Incomplete Course Modal
        function showIncompleteModal(courseName, progress, score) {
            const modal = document.getElementById('incomplete-modal');
            document.getElementById('incomplete-course-name').textContent = courseName;
            document.getElementById('incomplete-progress').textContent = progress;
            document.getElementById('incomplete-score').textContent = score;
            modal.style.display = 'flex';
        }

        function closeIncompleteModal() {
            document.getElementById('incomplete-modal').style.display = 'none';
        }

        // Close modals on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDownloadModal();
                closeIncompleteModal();
            }
        });

        // Close modals on background click
        window.onclick = function(event) {
            const downloadModal = document.getElementById('download-modal');
            const incompleteModal = document.getElementById('incomplete-modal');
            if (event.target === downloadModal) {
                closeDownloadModal();
            }
            if (event.target === incompleteModal) {
                closeIncompleteModal();
            }
        };
    </script>
@endpush

<!-- Download Modal -->
<div id="download-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); width: 90%; max-width: 500px; overflow: hidden; animation: slideIn 0.3s ease-out;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #5D3FFF, #7C3AED); padding: 24px; text-align: center; position: relative;">
            <div style="width: 64px; height: 64px; background: white; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <svg width="32" height="32" style="color: #5D3FFF;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;">Sertifikat Tersedia!</h3>
            <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">Selamat telah menyelesaikan pelatihan</p>
        </div>

        <!-- Content -->
        <div style="padding: 32px 24px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <p style="color: #64748B; font-size: 0.9rem; margin-bottom: 8px; font-family: 'Plus Jakarta Sans', sans-serif;">Kursus:</p>
                <h4 id="modal-course-name" style="color: #1E293B; font-size: 1.1rem; font-weight: 600; margin: 0 0 16px 0; font-family: 'Plus Jakarta Sans', sans-serif;"></h4>
                <div style="background: #F1F5F9; padding: 12px 16px; border-radius: 8px; display: inline-block;">
                    <p style="color: #64748B; font-size: 0.85rem; margin: 0 0 4px 0; font-family: 'Plus Jakarta Sans', sans-serif;">Nomor Sertifikat:</p>
                    <p id="modal-cert-number" style="color: #5D3FFF; font-weight: 600; font-size: 1rem; margin: 0; font-family: 'Courier New', monospace;"></p>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <button onclick="closeDownloadModal()" style="flex: 1; background: #F1F5F9; color: #64748B; border: none; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                    Batal
                </button>
                <a id="modal-download-link" href="#" style="flex: 2; background: linear-gradient(135deg, #5D3FFF, #7C3AED); color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(93,63,255,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Incomplete Course Modal -->
<div id="incomplete-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); width: 90%; max-width: 500px; overflow: hidden; animation: slideIn 0.3s ease-out;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #F59E0B, #F97316); padding: 24px; text-align: center;">
            <div style="width: 64px; height: 64px; background: white; border-radius: 50%; margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                <svg width="32" height="32" style="color: #F59E0B;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 style="margin: 0; color: white; font-size: 1.5rem; font-weight: 700; font-family: 'Plus Jakarta Sans', sans-serif;">Pelatihan Belum Selesai</h3>
            <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 0.95rem; font-family: 'Plus Jakarta Sans', sans-serif;">Selesaikan persyaratan berikut</p>
        </div>

        <!-- Content -->
        <div style="padding: 32px 24px;">
            <div style="text-align: center; margin-bottom: 24px;">
                <h4 id="incomplete-course-name" style="color: #1E293B; font-size: 1.1rem; font-weight: 600; margin: 0 0 24px 0; font-family: 'Plus Jakarta Sans', sans-serif;"></h4>
                
                <div style="background: #FEF3C7; border: 2px solid #FCD34D; border-radius: 12px; padding: 20px; margin-bottom: 16px;">
                    <p style="color: #92400E; font-size: 0.9rem; font-weight: 600; margin-bottom: 16px; font-family: 'Plus Jakarta Sans', sans-serif;">Persyaratan Sertifikat:</p>
                    <div style="display: flex; justify-content: space-around; gap: 16px;">
                        <div>
                            <p style="color: #78350F; font-size: 0.85rem; margin: 0 0 4px 0; font-family: 'Plus Jakarta Sans', sans-serif;">Progress Anda</p>
                            <p style="color: #92400E; font-size: 1.75rem; font-weight: 700; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;"><span id="incomplete-progress"></span>%</p>
                            <p style="color: #78350F; font-size: 0.75rem; margin: 4px 0 0 0; font-family: 'Plus Jakarta Sans', sans-serif;">Target: 100%</p>
                        </div>
                        <div style="border-left: 2px solid #FCD34D; height: auto;"></div>
                        <div>
                            <p style="color: #78350F; font-size: 0.85rem; margin: 0 0 4px 0; font-family: 'Plus Jakarta Sans', sans-serif;">Nilai Anda</p>
                            <p style="color: #92400E; font-size: 1.75rem; font-weight: 700; margin: 0; font-family: 'Plus Jakarta Sans', sans-serif;"><span id="incomplete-score"></span>/100</p>
                            <p style="color: #78350F; font-size: 0.75rem; margin: 4px 0 0 0; font-family: 'Plus Jakarta Sans', sans-serif;">Target: ≥70</p>
                        </div>
                    </div>
                </div>

                <div style="background: #EFF6FF; border-radius: 8px; padding: 16px; text-align: left;">
                    <p style="color: #1E40AF; font-size: 0.9rem; margin: 0; line-height: 1.6; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <strong>Tips:</strong> Selesaikan semua modul pembelajaran dan kerjakan quiz final dengan baik untuk mendapatkan sertifikat.
                    </p>
                </div>
            </div>

            <button onclick="closeIncompleteModal()" style="width: 100%; background: linear-gradient(135deg, #F59E0B, #F97316); color: white; border: none; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: all 0.2s; font-family: 'Plus Jakarta Sans', sans-serif;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(245,158,11,0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                Mengerti
            </button>
        </div>
    </div>
</div>

<style>
@keyframes slideIn {
    from { transform: translateY(-50px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
