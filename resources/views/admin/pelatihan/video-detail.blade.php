@extends('layouts.template')

@section('title', $video->judul)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="{{ asset('css/admin/pelatihan-video.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
{{-- <style>
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
        color: #667eea;
    }
    
    .material-type {
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .check-icon {
        flex-shrink: 0;
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
</style> --}}
@endpush

@section('content')
{{-- Topbar Pengajar --}}
@role('pengajar')
@include('components.topbar-pengajar')
@endrole

@php
    $fileVideo = $video->file_video;
    $youtubeUrl = $video->youtube_url;
    $isYoutube = !empty($youtubeUrl) || (!empty($fileVideo) && (str_contains($fileVideo, 'youtube.com') || str_contains($fileVideo, 'youtu.be')));
    $youtubeId = '';
    
    if ($isYoutube) {
        // First check youtube_url column
        $urlToCheck = !empty($youtubeUrl) ? $youtubeUrl : $fileVideo;
        // Extract YouTube video ID from embed URL or regular URL
        if (preg_match('/embed\/([^\/?]+)/', $urlToCheck, $matches)) {
            $youtubeId = $matches[1];
        } elseif (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $urlToCheck, $matches)) {
            $youtubeId = $matches[1];
        }
    }
    
    // Check if local video file exists
    $hasValidLocalVideo = !empty($fileVideo) && !$isYoutube && \Illuminate\Support\Facades\Storage::disk('public')->exists($fileVideo);
@endphp
<div class="page-container @role('pengajar') with-topbar @endrole"
    
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $video->modul->kursus->slug) }}?open_modul={{ $video->modul->slug }}" class="back-btn" onclick="navigateToModul(event, this.href)">
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
                    @if($isYoutube && !empty($youtubeId))
                        <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                            <iframe 
                                src="https://www.youtube.com/embed/{{ $youtubeId }}" 
                                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    @elseif($hasValidLocalVideo)
                        <video controls id="localVideoPlayer">
                            <source src="{{ asset('storage/' . $fileVideo) }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    @else
                        <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px; background: #f1f5f9; border-radius: 12px;">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                                <path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p style="color: #64748b; margin-top: 1rem; font-size: 0.875rem;">Video belum tersedia</p>
                        </div>
                    @endif
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
                        <div class="info-value">{{ $video->modul->judul ?? '-' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Kursus</div>
                        <div class="info-value">{{ $video->modul->kursus->judul ?? '-' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Pengajar</div>
                        <div class="info-value">{{ $video->modul->kursus->pengajar->name ?? '-' }}</div>
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
                            $isCurrent = ($itemType === 'video' && $itemData->id === $video->id);
                            
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
                        
                                <a href="{{ route($routeName, $itemData->slug) }}" class="material-item {{ $isCurrent ? 'active' : '' }}">
                            
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

@push('scripts')
@if($isYoutube && !empty($youtubeId))
<script src="https://www.youtube.com/iframe_api"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoId = {{ $video->id }};
    const isYouTube = {{ $isYoutube ? 'true' : 'false' }};
    const youtubeVideoId = '{{ $youtubeId ?? '' }}';
    let lastSavedTime = 0;
    let isCompleted = {{ isset($videoCompleted) && $videoCompleted ? 'true' : 'false' }};
    const COMPLETION_THRESHOLD = 0.95; // 95% of video duration
    
    // Show completion badge if already completed
    if (isCompleted) {
        showCompletionBadge();
    }
    
    // For YouTube videos, use YouTube IFrame API to track progress
    if (isYouTube && youtubeVideoId) {
        let ytPlayer = null;
        let ytProgressInterval = null;
        
        // Replace iframe with div for YouTube API
        const iframeContainer = document.querySelector('.video-player > div');
        if (iframeContainer) {
            const oldIframe = iframeContainer.querySelector('iframe');
            if (oldIframe) {
                const playerDiv = document.createElement('div');
                playerDiv.id = 'ytPlayer';
                playerDiv.style.cssText = 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;';
                oldIframe.replaceWith(playerDiv);
            }
        }
        
        // Initialize YouTube player when API is ready
        window.onYouTubeIframeAPIReady = function() {
            ytPlayer = new YT.Player('ytPlayer', {
                videoId: youtubeVideoId,
                playerVars: {
                    'autoplay': 0,
                    'modestbranding': 1,
                    'rel': 0
                },
                events: {
                    'onStateChange': onYTPlayerStateChange
                }
            });
        };
        
        function onYTPlayerStateChange(event) {
            // YT.PlayerState.PLAYING = 1
            if (event.data === 1) {
                // Start tracking progress
                ytProgressInterval = setInterval(checkYTProgress, 5000);
            } else {
                // Stop tracking when paused/ended
                clearInterval(ytProgressInterval);
                checkYTProgress(); // Save current progress
            }
        }
        
        function checkYTProgress() {
            if (!ytPlayer || isCompleted) return;
            
            try {
                const currentTime = ytPlayer.getCurrentTime();
                const duration = ytPlayer.getDuration();
                
                if (duration > 0) {
                    const watchTime = Math.floor(currentTime);
                    const totalDuration = Math.floor(duration);
                    const progress = currentTime / duration;
                    
                    // Only save if significant progress
                    if (watchTime - lastSavedTime >= 5 || progress >= COMPLETION_THRESHOLD) {
                        lastSavedTime = watchTime;
                        saveVideoProgress(watchTime, totalDuration, progress >= COMPLETION_THRESHOLD);
                    }
                }
            } catch (e) {
                console.log('YT progress check error:', e);
            }
        }
    }
    
    // For local videos, track progress automatically
    const videoElement = document.getElementById('localVideoPlayer');
    if (videoElement) {
        function saveLocalVideoProgress() {
            if (isCompleted) return;
            
            const watchTime = Math.floor(videoElement.currentTime);
            const totalDuration = Math.floor(videoElement.duration);
            const progress = videoElement.currentTime / videoElement.duration;
            
            // Only save if significant progress (at least 5 seconds since last save)
            if (watchTime - lastSavedTime < 5 && progress < COMPLETION_THRESHOLD) return;
            
            lastSavedTime = watchTime;
            saveVideoProgress(watchTime, totalDuration, progress >= COMPLETION_THRESHOLD);
        }
        
        // Save progress periodically while playing
        let progressInterval = null;
        
        videoElement.addEventListener('play', function() {
            progressInterval = setInterval(saveLocalVideoProgress, 5000);
        });
        
        videoElement.addEventListener('pause', function() {
            clearInterval(progressInterval);
            saveLocalVideoProgress();
        });
        
        videoElement.addEventListener('ended', function() {
            clearInterval(progressInterval);
            const totalDuration = Math.floor(videoElement.duration);
            saveVideoProgress(totalDuration, totalDuration, true);
        });
        
        // Also check on timeupdate for more accurate 95% detection
        videoElement.addEventListener('timeupdate', function() {
            if (isCompleted) return;
            const progress = videoElement.currentTime / videoElement.duration;
            if (progress >= COMPLETION_THRESHOLD) {
                saveLocalVideoProgress();
            }
        });
    }
    
    // Save video progress to server
    function saveVideoProgress(watchTime, totalDuration, forceComplete = false) {
        if (isCompleted) return;
        
        fetch('{{ route("user.progress.video") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                video_id: videoId,
                watch_time: forceComplete ? totalDuration : watchTime,
                total_duration: totalDuration
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && (data.data.completed || forceComplete)) {
                isCompleted = true;
                showCompletionBadge();
                updateNavigationIcon(videoId, 'video');
            }
        })
        .catch(error => console.log('Progress save error:', error));
    }
    
    // Show completion badge
    function showCompletionBadge() {
        const videoHeader = document.querySelector('.video-header');
        if (videoHeader && !document.querySelector('.completion-badge')) {
            const badge = document.createElement('div');
            badge.className = 'completion-badge';
            badge.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 20 20" fill="white">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Selesai
            `;
            badge.style.cssText = 'display: inline-flex; align-items: center; gap: 0.5rem; background: #10B981; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600; margin-top: 0.75rem;';
            videoHeader.appendChild(badge);
        }
    }
    
    // Update navigation icon to show checkmark
    function updateNavigationIcon(itemId, itemType) {
        const navItem = document.querySelector(`a[href*="${itemType}/${itemId}"]`);
        if (navItem && !navItem.querySelector('.completed-check')) {
            const checkIcon = document.createElement('svg');
            checkIcon.className = 'completed-check';
            checkIcon.setAttribute('width', '16');
            checkIcon.setAttribute('height', '16');
            checkIcon.setAttribute('viewBox', '0 0 20 20');
            checkIcon.setAttribute('fill', '#10B981');
            checkIcon.innerHTML = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>';
            navItem.appendChild(checkIcon);
        }
    }
});
</script>
@endpush
@endsection
