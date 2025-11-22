@extends('layouts.template')

@section('title', $video->judul)

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
    
    .video-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        color: white;
    }
    
    .video-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .video-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .video-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    
    .video-body {
        padding: 2rem;
    }
    
    .video-player {
        margin-bottom: 2rem;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .video-player video {
        width: 100%;
        height: auto;
        max-height: 500px;
        display: block;
    }
    
    .video-description {
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
        background: #EEF2FF;
        border-color: #667eea;
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
        background: #EDE9FE;
    }
    
    .material-icon.video.active {
        background: #667eea;
    }
    
    .material-icon.pdf {
        background: #FEE2E2;
    }
    
    .material-icon.pdf.active {
        background: #DC2626;
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
        color: #667eea;
    }
    
    .material-type {
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .check-icon {
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="page-container">
    
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $video->modul->kursus_id) }}" class="back-btn">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali ke Modul
    </a>

    <div class="content-wrapper">
        
        <!-- Main Content -->
        <div class="main-content">
            
            <!-- Video Header -->
            <div class="video-header">
                <div class="video-meta">
                    <div class="video-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" fill="white"/>
                            <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="white" stroke-width="2"/>
                        </svg>
                    </div>
                    <span style="font-size: 0.875rem; opacity: 0.9;">{{ $video->modul->judul }}</span>
                </div>
                <h1 class="video-title">{{ $video->judul }}</h1>
            </div>

            
            <!-- Video Body -->
            <div class="video-body">
                
                <!-- Video Player -->
                <div class="video-player">
                    <video controls>
                        <source src="{{ asset('storage/' . $video->file_video) }}" type="video/mp4">
                        Browser Anda tidak mendukung tag video.
                    </video>
                </div>

                <!-- Description -->
                @if($video->deskripsi)
                <div class="video-description">
                    <h3 class="section-title">Deskripsi</h3>
                    <p class="description-text">{{ $video->deskripsi }}</p>
                </div>
                @endif

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
                        <div class="info-value">{{ $video->modul->judul }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Kursus</div>
                        <div class="info-value">{{ $video->modul->kursus->judul }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Pengajar</div>
                        <div class="info-value">{{ $video->modul->kursus->pengajar->name }}</div>
                    </div>
                </div>
            </div>

            <!-- Related Materials -->
            <div class="sidebar-card">
                <h3 class="section-title" style="margin-bottom: 1rem;">Materi Lainnya</h3>
                
                <div class="materials-list">
                    @foreach($allItems as $item)
                        @php
                            $isVideo = $item['type'] === 'video';
                            $itemData = $item['data'];
                            $isCurrent = ($isVideo && $itemData->id === $video->id);
                            $routeName = $isVideo ? 'admin.video.show' : 'admin.materi.show';
                        @endphp
                        
                        <a href="{{ route($routeName, $itemData->id) }}" class="material-item {{ $isCurrent ? 'active' : '' }}">
                            
                            <div class="material-icon {{ $isVideo ? 'video' : 'pdf' }} {{ $isCurrent ? 'active' : '' }}">
                                @if($isVideo)
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" fill="{{ $isCurrent ? 'white' : '#7C3AED' }}"/>
                                        <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke="{{ $isCurrent ? 'white' : '#7C3AED' }}" stroke-width="2"/>
                                    </svg>
                                @else
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                        <path d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" stroke="{{ $isCurrent ? 'white' : '#DC2626' }}" stroke-width="2" fill="none"/>
                                    </svg>
                                @endif
                            </div>
                            
                            <div class="material-info">
                                <div class="material-title">{{ $itemData->judul }}</div>
                                <div class="material-type">{{ $isVideo ? 'Video' : 'PDF' }}</div>
                            </div>

                            @if($isCurrent)
                                <svg class="check-icon" width="16" height="16" viewBox="0 0 20 20" fill="#667eea">
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
@endsection
