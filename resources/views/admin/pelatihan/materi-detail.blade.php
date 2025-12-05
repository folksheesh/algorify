@extends('layouts.template')

@section('title', $materi->judul)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }
    
    .page-container {
        padding: 2rem;
        background: #F8FAFC;
        min-height: 100vh;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: opacity 0.2s;
    }
    
    .back-btn:hover {
        opacity: 0.8;
    }
    
    .content-wrapper {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 1.5rem;
    }
    
    .main-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    
    .pdf-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        color: white;
    }
    
    .pdf-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .pdf-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .pdf-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    
    .pdf-body {
        padding: 2rem;
    }
    
    .pdf-viewer {
        margin-bottom: 2rem;
        background: #F9FAFB;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        border: 2px dashed #D1D5DB;
    }
    
    .pdf-file-icon {
        width: 64px;
        height: 64px;
        margin: 0 auto 1rem;
    }
    
    .pdf-filename {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 0.5rem;
    }
    
    .open-pdf-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: #DC2626;
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        margin-top: 1rem;
        transition: background 0.2s;
    }
    
    .open-pdf-btn:hover {
        background: #B91C1C;
    }
    
    .pdf-description {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
    }
    
    .description-text {
        color: #6B7280;
        line-height: 1.6;
    }
    
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .sidebar-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
    }
    
    .info-label {
        font-size: 0.75rem;
        color: #6B7280;
        margin-bottom: 0.25rem;
    }
    
    .info-value {
        font-weight: 600;
        color: #1F2937;
    }
    
    .materials-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .material-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    
    .material-item:hover {
        background: #F9FAFB;
    }
    
    .material-item.active {
        background: #FEF2F2;
        border-color: #DC2626;
    }
    
    .material-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .material-icon.video {
        background: #EEF2FF;
    }
    
    .material-icon.video.active {
        background: #667eea;
    }
    
    .material-icon.pdf {
        background: #FEF2F2;
    }
    
    .material-icon.pdf.active {
        background: #EF4444;
    }
    
    .material-icon.bacaan {
        background: #FEF2F2;
    }
    
    .material-icon.bacaan.active {
        background: #EF4444;
    }
    
    .material-icon.quiz {
        background: #ECFDF5;
    }
    
    .material-icon.quiz.active {
        background: #10B981;
    }
    
    .material-icon.ujian {
        background: #FEF3C7;
    }
    
    .material-icon.ujian.active {
        background: #F59E0B;
    }
    
    .material-info {
        flex: 1;
        min-width: 0;
    }
    
    .material-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1F2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .material-item.active .material-title {
        color: #DC2626;
    }
    
    .material-type {
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .check-icon {
        flex-shrink: 0;
    }

    .materi-content {
        font-size: 1rem;
    }

    .materi-content h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 2rem 0 1rem;
        color: #1F2937;
    }

    .materi-content h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 1.5rem 0 1rem;
        color: #1F2937;
    }

    .materi-content h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 1.25rem 0 0.75rem;
        color: #374151;
    }

    .materi-content p {
        margin-bottom: 1rem;
    }

    .materi-content ul, .materi-content ol {
        margin: 1rem 0 1rem 1.5rem;
    }

    .materi-content li {
        margin-bottom: 0.5rem;
    }

    .materi-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .materi-content code {
        background: #F3F4F6;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
    }

    .materi-content pre {
        background: #1F2937;
        color: #F9FAFB;
        padding: 1rem;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1rem 0;
    }

    .materi-content pre code {
        background: transparent;
        padding: 0;
        border-radius: 0;
        color: inherit;
    }

    .materi-content blockquote {
        border-left: 4px solid #667eea;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6B7280;
    }

    .complete-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: #10B981;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .complete-btn:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .complete-btn.completed {
        background: #6B7280;
    }

    .complete-btn.completed:hover {
        background: #4B5563;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        z-index: 10000;
        transform: translateX(120%);
        transition: transform 0.3s ease;
        max-width: 380px;
        border-left: 4px solid #EF4444;
    }

    .toast-notification.active {
        transform: translateX(0);
    }

    .toast-notification.success {
        border-left-color: #10B981;
    }

    .toast-notification.success .toast-icon {
        color: #10B981;
    }

    .toast-notification.error {
        border-left-color: #EF4444;
    }

    .toast-notification.error .toast-icon {
        color: #EF4444;
    }

    .toast-notification.warning {
        border-left-color: #F59E0B;
    }

    .toast-notification.warning .toast-icon {
        color: #F59E0B;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        color: #EF4444;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 14px;
        color: #1F2937;
        margin-bottom: 2px;
    }

    .toast-message {
        font-size: 13px;
        color: #6B7280;
    }
    
    /* Topbar Layout Adjustment for Pengajar */
    .page-container.with-topbar {
        padding-top: calc(64px + 2rem);
    }
    
    @media (max-width: 992px) {
        .page-container.with-topbar {
            padding-top: calc(64px + 1rem);
        }
    }
</style>
@endpush

@push('scripts')
<!-- Toast Notification -->
<div id="toastNotification" class="toast-notification">
    <svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <div class="toast-content">
        <div class="toast-title" id="toastTitle">Error</div>
        <div class="toast-message" id="toastMessage">Terjadi kesalahan</div>
    </div>
</div>
<script>
    function showToast(title, message, type = 'error') {
        const toast = document.getElementById('toastNotification');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        
        toast.className = 'toast-notification ' + type;
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toast.classList.add('active');
        
        setTimeout(() => {
            toast.classList.remove('active');
        }, 4000);
    }

    function markAsComplete() {
        const btn = document.querySelector('.complete-btn');
        const btnText = document.getElementById('completeBtnText');
        
        // TODO: Implement actual completion tracking with backend
        // For now, just toggle the button state
        if (btn.classList.contains('completed')) {
            btn.classList.remove('completed');
            btnText.textContent = 'Tandai Sudah Selesai';
        } else {
            btn.classList.add('completed');
            btnText.textContent = '✓ Sudah Selesai';
        }
    }
</script>
@endpush

@section('content')
{{-- Topbar Pengajar --}}
@role('pengajar')
@include('components.topbar-pengajar')
@endrole

<div class="page-container @role('pengajar') with-topbar @endrole">
    
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $materi->modul->kursus_id) }}?open_modul={{ $materi->modul_id }}" class="back-btn" onclick="navigateToModul(event, this.href)">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali ke Modul
    </a>
    <script>
        function navigateToModul(e, url) {
            e.preventDefault();
            // Replace current history entry so back won't return here
            window.history.replaceState(null, '', url);
            window.location.href = url;
        }
    </script>

    <div class="content-wrapper">
        
        <!-- Main Content -->
        <div class="main-content">
            
            <!-- Reading Header -->
            <div class="pdf-header">
                <div class="pdf-meta">
                    <div class="pdf-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span style="font-size: 0.875rem; opacity: 0.9;">{{ $materi->modul->judul }}</span>
                </div>
                <h1 class="pdf-title">{{ $materi->judul }}</h1>
            </div>

            
            <!-- Reading Content -->
            <div class="pdf-body">

                <!-- Description -->
                @if($materi->deskripsi)
                <div class="pdf-description">
                    <p class="description-text" style="font-size: 1.125rem; font-style: italic; color: #6B7280; border-left: 4px solid #667eea; padding-left: 1rem; margin-bottom: 2rem;">{{ $materi->deskripsi }}</p>
                </div>
                @endif

                <!-- Main Content -->
                <div class="materi-content" style="line-height: 1.8; color: #374151;">
                    {!! $materi->konten !!}
                </div>

                <!-- Mark as Complete Button (for peserta role) -->
                @hasrole('peserta')
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px solid #E5E7EB; text-align: center;">
                    <button id="markAsReadBtn" class="btn-mark-read" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Tandai Sudah Dibaca
                    </button>
                </div>
                @endhasrole

            </div>

        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            
            <!-- Module Info -->
            <div class="sidebar-card">
                <h3 class="section-title" style="margin-bottom: 1rem;">Informasi Modul</h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Modul</div>
                        <div class="info-value">{{ $materi->modul->judul ?? '-' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Kursus</div>
                        <div class="info-value">{{ $materi->modul->kursus->judul ?? '-' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Pengajar</div>
                        <div class="info-value">{{ $materi->modul->kursus->pengajar->name ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Related Materials -->
            <div class="sidebar-card">
                <h3 class="section-title" style="margin-bottom: 1rem;">Materi Lainnya</h3>
                
                <div class="materials-list">
                    @foreach($allItems as $item)
                        @php
                            $itemType = $item['type'];
                            $itemData = $item['data'];
                            $isCurrent = ($itemType === 'bacaan' && $itemData->id === $materi->id);
                            
                            // Determine route based on type
                            if ($itemType === 'video') {
                                $routeName = 'admin.video.show';
                            } elseif ($itemType === 'bacaan') {
                                $routeName = 'admin.materi.show';
                            } else {
                                $routeName = 'admin.ujian.show';
                            }
                            
                            // Determine icon class
                            $iconClass = match($itemType) {
                                'video' => 'video',
                                'bacaan' => 'bacaan',
                                'quiz' => 'quiz',
                                'ujian' => 'ujian',
                                default => 'video'
                            };
                            
                            // Determine label
                            $typeLabel = match($itemType) {
                                'video' => 'Video',
                                'bacaan' => 'Bacaan',
                                'quiz' => 'Quiz',
                                'ujian' => 'Ujian',
                                default => 'Materi'
                            };
                        @endphp
                        
                        <a href="{{ route($routeName, $itemData->id) }}" class="material-item {{ $isCurrent ? 'active' : '' }}">
                            
                            @if($item['completed'] ?? false)
                                {{-- Icon Centang Hijau untuk item yang sudah selesai --}}
                                <div class="material-icon" style="background: #D1FAE5;">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#10B981">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @else
                                <div class="material-icon {{ $iconClass }} {{ $isCurrent ? 'active' : '' }}">
                                    @if($itemType === 'video')
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="{{ $isCurrent ? 'white' : '#667eea' }}">
                                            <path d="M6 4l10 6-10 6V4z"/>
                                        </svg>
                                    @elseif($itemType === 'bacaan')
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="{{ $isCurrent ? 'white' : '#EF4444' }}">
                                            <path d="M4 4a2 2 0 012-2h8l4 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                        </svg>
                                    @elseif($itemType === 'quiz')
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="{{ $isCurrent ? 'white' : '#10B981' }}">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="{{ $isCurrent ? 'white' : '#F59E0B' }}">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="material-info">
                                <div class="material-title">{{ $itemData->judul }}</div>
                                <div class="material-type" style="{{ ($item['completed'] ?? false) ? 'color: #10B981;' : '' }}">
                                    @if($item['completed'] ?? false)
                                        <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                            <svg width="10" height="10" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Selesai
                                        </span>
                                    @else
                                        {{ $typeLabel }}
                                    @endif
                                </div>
                            </div>

                            @if($isCurrent && !($item['completed'] ?? false))
                                <svg class="check-icon" width="16" height="16" viewBox="0 0 20 20" fill="#10B981">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const markReadBtn = document.getElementById('markAsReadBtn');
    const materiId = {{ $materi->id }};
    const isAlreadyCompleted = {{ isset($materiCompleted) && $materiCompleted ? 'true' : 'false' }};
    
    // If already completed, update button state
    if (isAlreadyCompleted && markReadBtn) {
        markReadBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            Sudah Dibaca
        `;
        markReadBtn.style.background = '#10B981';
        markReadBtn.classList.add('completed');
        markReadBtn.disabled = true;
    }
    
    if (markReadBtn && !isAlreadyCompleted) {
        markReadBtn.addEventListener('click', function() {
            // Disable button while processing
            markReadBtn.disabled = true;
            markReadBtn.innerHTML = `
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="animation: spin 1s linear infinite;">
                    <circle cx="12" cy="12" r="10" stroke-width="4" stroke-opacity="0.25"/>
                    <path d="M12 2a10 10 0 0 1 10 10" stroke-width="4"/>
                </svg>
                Menyimpan...
            `;
            
            fetch('{{ route("user.progress.reading") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    materi_id: materiId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show completion state
                    markReadBtn.innerHTML = `
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Sudah Dibaca
                    `;
                    markReadBtn.style.background = '#10B981';
                    markReadBtn.classList.add('completed');
                    
                    // Update progress bar if exists
                    if (data.course_progress) {
                        updateProgressBar(data.course_progress.percentage);
                    }
                } else {
                    showToast('Gagal', 'Gagal menandai materi: ' + data.message, 'error');
                    resetButton();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Terjadi kesalahan saat menandai materi', 'error');
                resetButton();
            });
            
            function resetButton() {
                markReadBtn.disabled = false;
                markReadBtn.innerHTML = `
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Tandai Sudah Dibaca
                `;
            }
        });
    }
    
    function updateProgressBar(percentage) {
        const progressBar = document.querySelector('.course-progress-bar');
        const progressText = document.querySelector('.course-progress-text');
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }
        if (progressText) {
            progressText.textContent = percentage + '% Selesai';
        }
    }
});
</script>
<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
.btn-mark-read.completed {
    cursor: default;
}
</style>
@endpush
@endsection
