@extends('layouts.template')

@section('title', 'Detail Kursus - ' . $kursus->judul)

@push('styles')
    <link rel="shortcut icon" href="{{ asset('template/assets/compiled/svg/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('template/custom/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/pelatihan-show.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SortableJS for Drag & Drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- Quill Editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>
@endpush

@section('content')
    {{-- Topbar Pengajar --}}
    @role('pengajar')
    @include('components.topbar-pengajar')
    @endrole
    
    <div class="dashboard-container @role('pengajar') with-topbar @endrole">
        @include('components.sidebar')
        <main class="main-content">
            <div class="page-container">
                <div class="course-header-card">
            <h1 class="course-title">{{ $kursus->judul }}</h1>
            <div class="course-meta">
                <div class="meta-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 5V10L13 13" stroke="white" stroke-width="2" stroke-linecap="round" fill="none"/>
                        <circle cx="10" cy="10" r="7" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <span>Segera</span>
                </div>
                <div class="meta-item">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 11C11.6569 11 13 9.65685 13 8C13 6.34315 11.6569 5 10 5C8.34315 5 7 6.34315 7 8C7 9.65685 8.34315 11 10 11Z" stroke="white" stroke-width="2" fill="none"/>
                        <path d="M15 15C15 12.7909 12.7614 11 10 11C7.23858 11 5 12.7909 5 15" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <span>0 Peserta</span>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2 class="card-title">Modul Kursus</h2>
                @hasanyrole('admin|super admin|pengajar')
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('admin.pelatihan.peserta', $kursus->id) }}" class="add-btn" style="background: linear-gradient(135deg, #10B981 0%, #059669 100%); text-decoration: none;">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Detail Peserta
                    </a>
                    <button class="add-btn" onclick="openAddWeekModal()">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 5V15M5 10H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Tambah Modul
                    </button>
                </div>
                @endhasanyrole
            </div>

            <div class="card-body">
                @if($kursus->modul->count() > 0)
                    <div class="weeks-list">
                        @foreach($kursus->modul as $index => $modul)
                            <div class="week-item" id="week-{{ $modul->id }}" data-id="{{ $modul->id }}">
                                <div class="week-header" onclick="toggleWeek({{ $modul->id }})">
                                    <div class="week-title-section">
                                        @hasanyrole('admin|super admin|pengajar')
                                        <div class="drag-handle-tooltip" data-tooltip="Seret untuk mengubah urutan">
                                            <svg class="drag-handle" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 20px; height: 20px; color: #667eea; margin-right: 0.5rem;">
                                                <line x1="4" y1="6" x2="20" y2="6"/>
                                                <line x1="4" y1="12" x2="20" y2="12"/>
                                                <line x1="4" y1="18" x2="20" y2="18"/>
                                            </svg>
                                        </div>
                                        @endhasanyrole
                                        <svg class="expand-icon" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="week-info">
                                            <div class="week-title">{{ $modul->judul }}</div>
                                            <div class="week-subtitle">Materi pembelajaran dan konten kursus</div>
                                        </div>
                                    </div>
                                    @hasanyrole('admin|super admin|pengajar')
                                    <div class="week-actions" onclick="event.stopPropagation()">
                                        <button class="action-btn edit-btn" onclick="event.stopPropagation(); editWeek({{ $modul->id }})" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                            </svg>
                                        </button>
                                        <button class="action-btn delete-btn" onclick="event.stopPropagation(); deleteWeek({{ $modul->id }}, '{{ $modul->judul }}')" title="Hapus">
                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </div>
                                    @endhasanyrole
                                </div>
                                <div class="week-content">
                                    @if($modul->deskripsi)
                                        <div class="week-description">{{ $modul->deskripsi }}</div>
                                    @endif
                                    
                                    <div class="sections-header">
                                        <h3 class="sections-title">Section Pembelajaran</h3>
                                        @hasanyrole('admin|super admin|pengajar')
                                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            <button class="add-section-btn" onclick="openVideoModal({{ $modul->id }})" style="background: #EEF2FF; color: #667eea; padding: 0.5rem 1rem; font-size: 0.75rem;">
                                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.25rem;">
                                                    <path d="M6 4l10 6-10 6V4z"/>
                                                </svg>
                                                Upload Video
                                            </button>
                                            <button class="add-section-btn" onclick="openPdfModal({{ $modul->id }})" style="background: #FEF2F2; color: #EF4444; padding: 0.5rem 1rem; font-size: 0.75rem;">
                                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.25rem;">
                                                    <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                Materi Bacaan
                                            </button>
                                            <button class="add-section-btn" onclick="openQuizModal({{ $modul->id }})" style="background: #ECFDF5; color: #10B981; padding: 0.5rem 1rem; font-size: 0.75rem;">
                                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.25rem;">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Buat Kuis
                                            </button>
                                            <button class="add-section-btn" onclick="openExamModal({{ $modul->id }})" style="background: #FEF3C7; color: #F59E0B; padding: 0.5rem 1rem; font-size: 0.75rem;">
                                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.25rem;">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                </svg>
                                                Buat Ujian
                                            </button>
                                        </div>
                                        @endhasanyrole
                                    </div>
                                    
                                    @php
                                        $allItems = collect();
                                        foreach($modul->video as $video) {
                                            $isCompleted = collect($completedItems ?? [])->contains(fn($item) => $item['type'] === 'video' && $item['id'] == $video->id);
                                            $allItems->push(['type' => 'video', 'data' => $video, 'urutan' => $video->urutan ?? 0, 'completed' => $isCompleted]);
                                        }
                                        foreach($modul->materi as $pdf) {
                                            $isCompleted = collect($completedItems ?? [])->contains(fn($item) => $item['type'] === 'materi' && $item['id'] == $pdf->id);
                                            $allItems->push(['type' => 'pdf', 'data' => $pdf, 'urutan' => ($pdf->urutan ?? 0) + 100, 'completed' => $isCompleted]);
                                        }
                                        foreach($modul->ujian as $ujian) {
                                            $type = $ujian->tipe === 'practice' ? 'quiz' : 'ujian';
                                            $isCompleted = collect($completedItems ?? [])->contains(fn($item) => $item['type'] === $type && $item['id'] == $ujian->id);
                                            $allItems->push(['type' => $type, 'data' => $ujian, 'urutan' => 200 + ($ujian->id ?? 0), 'completed' => $isCompleted]);
                                        }
                                        $allItems = $allItems->sortBy('urutan');
                                    @endphp
                                    
                                    @if($allItems->count() > 0)
                                        <div class="materi-list" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 1rem;">
                                            @foreach($allItems as $item)
                                                @php 
                                                    $data = $item['data']; 
                                                    $routeName = $item['type'] === 'video' ? 'admin.video.show' : ($item['type'] === 'pdf' ? 'admin.materi.show' : 'admin.ujian.show');
                                                @endphp
                                                <div class="materi-item" style="display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #F8FAFC; border-radius: 10px; border: 1px solid #E2E8F0;" data-id="{{ $data->id }}" data-type="{{ $item['type'] }}">
                                                    <a href="{{ route($routeName, $data->id) }}" style="display: flex; align-items: center; gap: 1rem; flex: 1; text-decoration: none; color: inherit;">
                                                        @if($item['completed'] ?? false)
                                                            {{-- Icon Centang Hijau untuk item yang sudah selesai --}}
                                                            <div style="width: 40px; height: 40px; background: #D1FAE5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="#10B981">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @elseif($item['type'] === 'video')
                                                            <div style="width: 40px; height: 40px; background: #EEF2FF; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="#667eea">
                                                                    <path d="M6 4l10 6-10 6V4z"/>
                                                                </svg>
                                                            </div>
                                                        @elseif($item['type'] === 'pdf')
                                                            <div style="width: 40px; height: 40px; background: #FEF2F2; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="#EF4444">
                                                                    <path d="M4 4a2 2 0 012-2h8l4 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                                                </svg>
                                                            </div>
                                                        @elseif($item['type'] === 'quiz')
                                                            <div style="width: 40px; height: 40px; background: #ECFDF5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="#10B981">
                                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @else
                                                            <div style="width: 40px; height: 40px; background: #FEF3C7; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="#F59E0B">
                                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div style="font-weight: 600; color: #1A1A1A; font-size: 0.875rem;">{{ $data->judul }}</div>
                                                            <div style="font-size: 0.75rem; color: {{ ($item['completed'] ?? false) ? '#10B981' : '#64748B' }}; margin-top: 0.25rem;">
                                                                @if($item['completed'] ?? false)
                                                                    <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                                                        <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor">
                                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                                        </svg>
                                                                        Selesai
                                                                    </span>
                                                                @elseif($item['type'] === 'video')
                                                                    Video
                                                                @elseif($item['type'] === 'pdf')
                                                                    Bacaan (PDF)
                                                                @elseif($item['type'] === 'quiz')
                                                                    Kuis
                                                                @else
                                                                    Ujian
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </a>
                                                    @hasanyrole('admin|super admin|pengajar')
                                                    <div style="display: flex; gap: 0.5rem;">
                                                        <button onclick="event.stopPropagation(); 
                                                            @if($item['type'] === 'video')
                                                                editVideo({{ $data->id }})
                                                            @elseif($item['type'] === 'pdf')
                                                                editMateri({{ $data->id }})
                                                            @elseif($item['type'] === 'quiz')
                                                                editUjian({{ $data->id }})
                                                            @else
                                                                editUjian({{ $data->id }})
                                                            @endif
                                                        " style="padding: 0.5rem; background: white; border: 1px solid #E2E8F0; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Edit">
                                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="#667eea">
                                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                            </svg>
                                                        </button>
                                                        <button onclick="event.stopPropagation(); 
                                                            @if($item['type'] === 'video')
                                                                deleteVideo({{ $data->id }}, '{{ $data->judul }}')
                                                            @elseif($item['type'] === 'pdf')
                                                                deleteMateri({{ $data->id }}, '{{ $data->judul }}')
                                                            @elseif($item['type'] === 'quiz')
                                                                deleteUjian({{ $data->id }}, '{{ $data->judul }}')
                                                            @else
                                                                deleteUjian({{ $data->id }}, '{{ $data->judul }}')
                                                            @endif
                                                        " style="padding: 0.5rem; background: white; border: 1px solid #E2E8F0; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center;" title="Hapus">
                                                            <svg width="16" height="16" viewBox="0 0 20 20" fill="#EF4444">
                                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    @endhasanyrole
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="empty-state" style="padding: 2rem 1rem;">
                                            <div class="empty-text">Belum ada item pembelajaran. Klik tombol di atas untuk menambahkan.</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <svg class="empty-icon" viewBox="0 0 64 64" fill="currentColor">
                            <rect x="8" y="12" width="48" height="40" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
                            <path d="M16 24H48M16 32H48M16 40H32" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <h3 class="empty-title">Belum ada modul</h3>
                        @hasanyrole('admin|super admin|pengajar')
                        <p class="empty-text">Klik tombol "Tambah Modul" untuk menambahkan modul pembelajaran</p>
                        @endhasanyrole
                    </div>
                @endif
            </div>
        </div>
            </div>
        </main>
    </div>

    <!-- Toast Notification untuk Drag & Drop -->
    <div id="dragToast" style="position: fixed; top: 20px; right: 20px; background: white; padding: 1rem 1.5rem; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: none; z-index: 10000; animation: slideIn 0.3s ease;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="#10B981">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="font-weight: 600; color: #1A1A1A; font-size: 0.875rem;">Urutan berhasil diperbarui</span>
        </div>
    </div>

    {{-- TOAST NOTIFICATION - Notifikasi di pojok kanan atas --}}
    <div id="toastNotification" class="toast-notification">
        <div class="toast-icon-wrapper" id="toastIconWrapper">
            <svg class="toast-icon" id="toastIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="toast-content">
            <div class="toast-title" id="toastTitle">Notifikasi</div>
            <div class="toast-msg" id="toastMessage">Pesan notifikasi</div>
        </div>
        <button class="toast-close-btn" onclick="closeToastNotif()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <style>
    .toast-notification { position: fixed; top: 20px; right: 20px; padding: 1rem 1.25rem; background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15); z-index: 10001; display: none; align-items: center; gap: 0.75rem; min-width: 320px; max-width: 450px; animation: toastSlideIn 0.3s ease; border-left: 4px solid #10B981; }
    .toast-notification.active { display: flex; }
    .toast-notification.success { border-left-color: #10B981; }
    .toast-notification.error { border-left-color: #EF4444; }
    .toast-notification.warning { border-left-color: #F59E0B; }
    .toast-notification.info { border-left-color: #3B82F6; }
    .toast-icon-wrapper { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .toast-notification.success .toast-icon-wrapper { background: #D1FAE5; color: #10B981; }
    .toast-notification.error .toast-icon-wrapper { background: #FEE2E2; color: #EF4444; }
    .toast-notification.warning .toast-icon-wrapper { background: #FEF3C7; color: #F59E0B; }
    .toast-notification.info .toast-icon-wrapper { background: #DBEAFE; color: #3B82F6; }
    .toast-icon { width: 20px; height: 20px; }
    .toast-content { flex: 1; }
    .toast-title { font-weight: 600; font-size: 0.875rem; color: #1E293B; margin-bottom: 0.125rem; }
    .toast-msg { font-size: 0.8125rem; color: #64748B; line-height: 1.4; }
    .toast-close-btn { background: transparent; border: none; cursor: pointer; padding: 0.25rem; color: #94A3B8; border-radius: 4px; transition: all 0.2s; }
    .toast-close-btn:hover { background: #F1F5F9; color: #64748B; }
    @keyframes toastSlideIn { from { opacity: 0; transform: translateX(100px); } to { opacity: 1; transform: translateX(0); } }
    @keyframes toastSlideOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(100px); } }
    </style>

    <!-- Modal Tambah/Edit Minggu -->
    <div class="modal-overlay" id="modalWeek">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="modalTitle">Tambah Modul</h2>
                    <p class="modal-subtitle">Masukkan informasi modul pembelajaran</p>
                </div>
                <button class="modal-close" onclick="closeWeekModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            
            <form id="formWeek" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="kursus_id" value="{{ $kursus->id }}">
                <input type="hidden" name="modul_id" id="modulId">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul Modul *</label>
                        <input type="text" name="judul" id="judul" class="form-input" placeholder="contoh: Pengenalan Dasar UI/UX Design" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-input form-textarea" placeholder="Masukkan deskripsi modul..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeWeekModal()">Batal</button>
                    <button type="submit" class="btn-submit" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload Video -->
    <div id="modalVideo" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="videoModalTitle">Upload Video</h2>
                    <p class="modal-subtitle">Upload file video pembelajaran</p>
                </div>
                <button class="modal-close" type="button" onclick="closeVideoModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <form id="formVideo" action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="videoMethod" value="POST">
                <input type="hidden" name="modul_id" id="videoModulId">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul Video *</label>
                        <input type="text" name="judul" id="videoJudul" class="form-input" placeholder="Contoh: Pengenalan Desain UI/UX" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="videoDeskripsi" class="form-input" rows="3" placeholder="Deskripsi singkat tentang video ini"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Upload File Video *</label>
                        <div class="upload-area" onclick="document.getElementById('videoFile').click()">
                            <div class="upload-icon" id="videoIcon">
                                <svg width="48" height="48" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 15L12 3M12 3L8 7M12 3L16 7" stroke="#667eea" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 15V18C3 19.1046 3.89543 20 5 20H19C20.1046 20 21 19.1046 21 18V15" stroke="#667eea" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748B;">Drag & drop video atau klik untuk browse</p>
                                <p style="font-size: 0.75rem; color: #94A3B8;">Format: MP4, MOV, AVI, MKV (Maks 100MB)</p>
                            </div>
                            <div id="videoPreview" style="display: none; font-size: 0.875rem; color: #667eea;"></div>
                        </div>
                        <input type="file" id="videoFile" name="file_video" accept="video/*" style="display: none;" onchange="previewFile(this, 'videoPreview', 'videoIcon')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeVideoModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Materi Bacaan -->
    <div id="modalPdf" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="pdfModalTitle">Buat Materi Bacaan</h2>
                    <p class="modal-subtitle">Buat materi bacaan dengan konten rich text</p>
                </div>
                <button class="modal-close" type="button" onclick="closePdfModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <form id="formPdf" action="{{ route('admin.materi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="pdfMethod" value="POST">
                <input type="hidden" name="modul_id" id="pdfModulId">
                <div class="modal-body" style="max-height: 55vh; overflow-y: auto;">
                    <div class="form-group">
                        <label class="form-label">Judul Bacaan *</label>
                        <input type="text" name="judul" id="pdfJudul" class="form-input" placeholder="Contoh: Pengenalan Dasar UI/UX" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi Singkat</label>
                        <textarea name="deskripsi" id="pdfDeskripsi" class="form-input" rows="2" placeholder="Deskripsi singkat yang muncul di preview"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konten Materi *</label>
                        <p style="font-size: 0.875rem; color: #6B7280; margin-bottom: 0.5rem;">💡 Gunakan tombol image di toolbar untuk menyisipkan gambar ke dalam konten</p>
                        <input type="hidden" name="konten" id="materiKonten">
                        <div id="quillEditor" style="height: 400px; background: white; border: 1px solid #E5E7EB; border-radius: 8px;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closePdfModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Kuis -->
    <div id="modalQuiz" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="quizModalTitle">Buat Kuis</h2>
                    <p class="modal-subtitle">Buat kuis untuk evaluasi pembelajaran</p>
                </div>
                <button class="modal-close" type="button" onclick="closeQuizModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <form id="formQuiz" action="{{ route('admin.ujian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="quizMethod" value="POST">
                <input type="hidden" name="modul_id" id="quizModulId">
                <input type="hidden" name="tipe" value="quiz">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul Kuis *</label>
                        <input type="text" name="judul" id="quizJudul" class="form-input" placeholder="Contoh: Kuis Dasar UI/UX" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="quizDeskripsi" class="form-input" rows="3" placeholder="Deskripsi singkat tentang kuis ini"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Pengerjaan (menit) *</label>
                        <input type="number" name="waktu_pengerjaan" id="quizWaktu" class="form-input" placeholder="Contoh: 15" min="1" value="15" required>
                        <small style="color: #6B7280; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Masukkan waktu dalam menit (contoh: 15 untuk kuis singkat)</small>
                    </div>
                    <p style="font-size: 0.875rem; color: #6B7280; margin-top: 1rem; padding: 0.75rem; background: #F3F4F6; border-radius: 6px;">
                        💡 Soal dapat ditambahkan setelah kuis dibuat
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeQuizModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Buat Ujian -->
    <div id="modalExam" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="examModalTitle">Buat Ujian</h2>
                    <p class="modal-subtitle">Buat ujian akhir modul</p>
                </div>
                <button class="modal-close" type="button" onclick="closeExamModal()">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
            <form id="formExam" action="{{ route('admin.ujian.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="examMethod" value="POST">
                <input type="hidden" name="modul_id" id="examModulId">
                <input type="hidden" name="tipe" value="ujian">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Judul Ujian *</label>
                        <input type="text" name="judul" id="examJudul" class="form-input" placeholder="Contoh: Ujian Akhir Modul 1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="examDeskripsi" class="form-input" rows="3" placeholder="Deskripsi singkat tentang ujian ini"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Waktu Pengerjaan (menit) *</label>
                        <input type="number" name="waktu_pengerjaan" id="examWaktu" class="form-input" placeholder="Contoh: 60" min="1" required>
                        <small style="color: #6B7280; font-size: 0.75rem; margin-top: 0.25rem; display: block;">Masukkan waktu dalam menit (contoh: 60 untuk 1 jam)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeExamModal()">Batal</button>
                    <button type="submit" class="btn-submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Penghapusan -->
    <div class="modal-overlay" id="modalDelete">
        <div class="modal-container" style="max-width: 500px;">
            <div class="modal-body" style="padding: 2.5rem; text-align: center;">
                <div style="width: 64px; height: 64px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <svg width="32" height="32" viewBox="0 0 20 20" fill="#DC2626">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1A1A1A; margin-bottom: 0.5rem;">Konfirmasi Penghapusan</h2>
                <p id="deleteMessage" style="font-size: 0.875rem; color: #64748B; margin-bottom: 2rem;">Apakah Anda yakin ingin menghapus item ini?</p>
                <p style="font-size: 0.75rem; color: #EF4444; margin-bottom: 2rem;">Tindakan ini akan menghapus semua akses dan permissions admin ini. Proses ini tidak dapat dibatalkan</p>
                
                <div style="display: flex; gap: 1rem; justify-content: center;">
                    <button type="button" class="btn-cancel" onclick="closeDeleteModal()" style="flex: 1; max-width: 150px;">Batal</button>
                    <button type="button" class="btn-submit" id="confirmDeleteBtn" style="flex: 1; max-width: 150px; background: #DC2626 !important; border-color: #DC2626 !important;">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Check if there's a modul to open from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const openModulId = urlParams.get('open_modul');
            
            if (openModulId) {
                const weekItem = document.getElementById('week-' + openModulId);
                if (weekItem) {
                    weekItem.classList.add('expanded');
                    // Scroll to the modul
                    setTimeout(() => {
                        weekItem.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        });

        // Toast Notification Function
        let toastNotifTimeout = null;
        
        function showToastNotif(title, message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            const toastTitle = document.getElementById('toastTitle');
            const toastMessage = document.getElementById('toastMessage');
            
            if (toastNotifTimeout) clearTimeout(toastNotifTimeout);
            
            toastTitle.textContent = title;
            toastMessage.textContent = message;
            toast.className = 'toast-notification ' + type + ' active';
            
            const iconSvg = document.getElementById('toastIcon');
            if (type === 'success') {
                iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
            } else if (type === 'error') {
                iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            } else if (type === 'warning') {
                iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
            } else {
                iconSvg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            }
            
            toastNotifTimeout = setTimeout(() => closeToastNotif(), 4000);
        }
        
        function closeToastNotif() {
            const toast = document.getElementById('toastNotification');
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => {
                toast.classList.remove('active');
                toast.style.animation = '';
            }, 300);
        }

        function toggleWeek(id) {
            const weekItem = document.getElementById('week-' + id);
            weekItem.classList.toggle('expanded');
        }
        
        function addSection(modulId) {
            showToastNotif('Info', 'Fitur tambah section akan segera hadir', 'info');
            // TODO: Implement add section functionality
        }
        
        function openAddWeekModal() {
            const modal = document.getElementById('modalWeek');
            const form = document.getElementById('formWeek');
            const modalTitle = document.getElementById('modalTitle');
            
            modalTitle.textContent = 'Tambah Modul';
            form.reset();
            document.getElementById('formMethod').value = 'POST';
            form.action = '{{ route("admin.modul.store") }}';
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeWeekModal() {
            const modal = document.getElementById('modalWeek');
            const modalTitle = document.getElementById('modalTitle');
            const form = document.getElementById('formWeek');
            
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            form.reset();
            
            // Reset to add mode
            modalTitle.textContent = 'Tambah Modul';
            document.getElementById('formMethod').value = 'POST';
            form.action = '{{ route("admin.modul.store") }}';
        }
        
        function editWeek(id) {
            // Close any open modal first
            const modal = document.getElementById('modalWeek');
            modal.classList.remove('active');
            
            fetch(`/admin/modul/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formWeek');
                    const modalTitle = document.getElementById('modalTitle');
                    
                    modalTitle.textContent = 'Edit Modul';
                    form.reset();
                    
                    document.getElementById('formMethod').value = 'PUT';
                    form.action = `/admin/modul/${id}`;
                    
                    document.getElementById('modulId').value = data.id;
                    document.getElementById('judul').value = data.judul;
                    document.getElementById('deskripsi').value = data.deskripsi || '';
                    
                    // Wait a bit longer before opening
                    setTimeout(() => {
                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }, 150);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastNotif('Gagal', 'Gagal memuat data modul', 'error');
                });
        }
        
        let deleteItemId = null;
        let deleteItemType = null;
        
        function openDeleteModal(id, type, name) {
            deleteItemId = id;
            deleteItemType = type;
            
            const modal = document.getElementById('modalDelete');
            const message = document.getElementById('deleteMessage');
            
            if (type === 'modul') {
                message.textContent = `Apakah Anda yakin ingin menghapus Sub Modul: ${name}?`;
            } else if (type === 'video') {
                message.textContent = `Apakah Anda yakin ingin menghapus Video: ${name}?`;
            } else {
                message.textContent = `Apakah Anda yakin ingin menghapus PDF: ${name}?`;
            }
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeDeleteModal() {
            const modal = document.getElementById('modalDelete');
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            deleteItemId = null;
            deleteItemType = null;
        }
        
        function deleteWeek(id, name = '') {
            openDeleteModal(id, 'modul', name);
        }
        
        // Close modal on overlay click
        document.getElementById('modalWeek').addEventListener('click', function(e) {
            if (e.target === this) {
                closeWeekModal();
            }
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeWeekModal();
                closeSectionModal();
            }
        });
        
        // Section Modal Functions
        let currentModulId = null;
        
        function openSectionModal(modulId) {
            currentModulId = modulId;
            const modal = document.getElementById('modalSection');
            const form = document.getElementById('formSection');
            
            form.reset();
            document.getElementById('sectionMethod').value = 'POST';
            form.action = '{{ route("admin.materi.store") }}';
            document.getElementById('sectionModulId').value = modulId;
            
            // Reset modal title
            document.querySelector('#modalSection .modal-title').textContent = 'Tambah Item Baru';
            
            // Reset file previews
            document.getElementById('videoPreview').style.display = 'none';
            document.getElementById('videoIcon').style.display = 'flex';
            document.getElementById('pdfPreview').style.display = 'none';
            document.getElementById('pdfIcon').style.display = 'flex';
            
            // Reset to default (video)
            handleTypeChange('video');
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        

        
        function previewFile(input, previewId, iconId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const icon = document.getElementById(iconId);
            
            if (file) {
                preview.textContent = file.name;
                icon.style.display = 'none';
                preview.style.display = 'block';
            }
        }
        
        // Close modals on overlay click and setup form handlers
        document.addEventListener('DOMContentLoaded', function() {
            const modalIds = ['modalVideo', 'modalPdf', 'modalQuiz', 'modalExam'];
            const closeFunctions = {
                'modalVideo': closeVideoModal,
                'modalPdf': closePdfModal,
                'modalQuiz': closeQuizModal,
                'modalExam': closeExamModal
            };
            
            modalIds.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            closeFunctions[modalId]();
                        }
                    });
                }
            });
            
            // Video form submit
            const formVideo = document.getElementById('formVideo');
            if (formVideo) {
                formVideo.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const method = document.getElementById('videoMethod').value;
                    
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closeVideoModal();
                            location.reload();
                        }
                    });
                });
            }
            
            // Materi form submit
            const formPdf = document.getElementById('formPdf');
            if (formPdf) {
                formPdf.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Sync Quill content before submit
                    if (quillEditor) {
                        document.getElementById('materiKonten').value = quillEditor.root.innerHTML;
                    }
                    
                    const formData = new FormData(this);
                    const method = document.getElementById('pdfMethod').value;
                    
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            closePdfModal();
                            location.reload();
                        } else {
                            showToastNotif('Gagal', 'Gagal menyimpan materi: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToastNotif('Error', 'Terjadi kesalahan saat menyimpan materi', 'error');
                    });
                });
            }
            
            // Quiz form submit
            const formQuiz = document.getElementById('formQuiz');
            if (formQuiz) {
                formQuiz.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const method = document.getElementById('quizMethod').value;
                    
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    console.log('Submitting quiz form to:', this.action);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            closeQuizModal();
                            location.reload();
                        } else {
                            showToastNotif('Gagal', 'Gagal menyimpan kuis: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToastNotif('Error', 'Terjadi kesalahan saat menyimpan kuis', 'error');
                    });
                });
            }
            
            // Exam form submit
            const formExam = document.getElementById('formExam');
            if (formExam) {
                formExam.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const method = document.getElementById('examMethod').value;
                    
                    if (method === 'PUT') {
                        formData.append('_method', 'PUT');
                    }
                    
                    console.log('Submitting exam form to:', this.action);
                    
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            closeExamModal();
                            location.reload();
                        } else {
                            showToastNotif('Gagal', 'Gagal menyimpan ujian: ' + (data.message || 'Unknown error'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToastNotif('Error', 'Terjadi kesalahan saat menyimpan ujian', 'error');
                    });
                });
            }
        });
        
        // Modal Video Functions
        function openVideoModal(modulId) {
            const form = document.getElementById('formVideo');
            const modal = document.getElementById('modalVideo');
            
            // Reset form
            form.reset();
            document.getElementById('videoMethod').value = 'POST';
            form.action = '{{ route("admin.video.store") }}';
            document.getElementById('videoModulId').value = modulId;
            document.getElementById('videoModalTitle').textContent = 'Upload Video';
            
            // Reset preview
            document.getElementById('videoPreview').style.display = 'none';
            document.getElementById('videoIcon').style.display = 'block';
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeVideoModal() {
            const modal = document.getElementById('modalVideo');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Modal Materi Functions
        function openPdfModal(modulId) {
            const form = document.getElementById('formPdf');
            const modal = document.getElementById('modalPdf');
            
            // Reset form
            form.reset();
            document.getElementById('pdfMethod').value = 'POST';
            form.action = '{{ route("admin.materi.store") }}';
            document.getElementById('pdfModulId').value = modulId;
            document.getElementById('pdfModalTitle').textContent = 'Buat Materi Bacaan';
            
            // Reset Quill editor
            if (quillEditor) {
                quillEditor.setText('');
            }
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closePdfModal() {
            const modal = document.getElementById('modalPdf');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Modal Quiz Functions
        function openQuizModal(modulId) {
            const form = document.getElementById('formQuiz');
            const modal = document.getElementById('modalQuiz');
            
            // Reset form
            form.reset();
            document.getElementById('quizMethod').value = 'POST';
            form.action = '{{ route("admin.ujian.store") }}';
            document.getElementById('quizModulId').value = modulId;
            document.getElementById('quizModalTitle').textContent = 'Buat Kuis';
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeQuizModal() {
            const modal = document.getElementById('modalQuiz');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Modal Exam Functions
        function openExamModal(modulId) {
            const form = document.getElementById('formExam');
            const modal = document.getElementById('modalExam');
            
            // Reset form
            form.reset();
            document.getElementById('examMethod').value = 'POST';
            form.action = '{{ route("admin.ujian.store") }}';
            document.getElementById('examModulId').value = modulId;
            document.getElementById('examModalTitle').textContent = 'Buat Ujian';
            
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeExamModal() {
            const modal = document.getElementById('modalExam');
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Edit Video Function
        function editVideo(id) {
            fetch(`/admin/video/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formVideo');
                    const modal = document.getElementById('modalVideo');
                    
                    document.getElementById('videoMethod').value = 'PUT';
                    form.action = `/admin/video/${id}`;
                    document.getElementById('videoJudul').value = data.judul;
                    document.getElementById('videoDeskripsi').value = data.deskripsi || '';
                    document.getElementById('videoModulId').value = data.modul_id;
                    document.getElementById('videoModalTitle').textContent = 'Edit Video';
                    
                    if (data.file_video) {
                        document.getElementById('videoPreview').textContent = 'File saat ini: ' + data.file_video.split('/').pop();
                        document.getElementById('videoPreview').style.display = 'block';
                        document.getElementById('videoIcon').style.display = 'none';
                    }
                    
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastNotif('Error', 'Terjadi kesalahan saat mengambil data video', 'error');
                });
        }
        
        // Edit Materi Function
        function editMateri(id) {
            fetch(`/admin/materi/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('formPdf');
                    const modal = document.getElementById('modalPdf');
                    
                    document.getElementById('pdfMethod').value = 'PUT';
                    form.action = `/admin/materi/${id}`;
                    document.getElementById('pdfJudul').value = data.judul;
                    document.getElementById('pdfDeskripsi').value = data.deskripsi || '';
                    document.getElementById('pdfModulId').value = data.modul_id;
                    document.getElementById('pdfModalTitle').textContent = 'Edit Materi Bacaan';
                    
                    // Load konten to Quill editor
                    if (quillEditor && data.konten) {
                        quillEditor.root.innerHTML = data.konten;
                    }
                    
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastNotif('Error', 'Terjadi kesalahan saat mengambil data materi', 'error');
                });
        }
        
        function deleteVideo(id, name = '') {
            openDeleteModal(id, 'video', name);
        }
        
        function deleteMateri(id, name = '') {
            openDeleteModal(id, 'materi', name);
        }
        
        // Edit Kuis/Ujian Function
        function editUjian(id) {
            fetch(`/admin/ujian/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Determine if it's quiz or exam based on tipe
                    const isQuiz = data.tipe === 'practice';
                    const formId = isQuiz ? 'formQuiz' : 'formExam';
                    const modalId = isQuiz ? 'modalQuiz' : 'modalExam';
                    
                    const form = document.getElementById(formId);
                    const modal = document.getElementById(modalId);
                    
                    if (isQuiz) {
                        document.getElementById('quizMethod').value = 'PUT';
                        form.action = `/admin/ujian/${id}`;
                        document.getElementById('quizJudul').value = data.judul;
                        document.getElementById('quizDeskripsi').value = data.deskripsi || '';
                        document.getElementById('quizModulId').value = data.modul_id;
                        document.getElementById('quizWaktu').value = data.waktu_pengerjaan || 15;
                        document.getElementById('quizModalTitle').textContent = 'Edit Kuis';
                    } else {
                        document.getElementById('examMethod').value = 'PUT';
                        form.action = `/admin/ujian/${id}`;
                        document.getElementById('examJudul').value = data.judul;
                        document.getElementById('examDeskripsi').value = data.deskripsi || '';
                        document.getElementById('examModulId').value = data.modul_id;
                        document.getElementById('examWaktu').value = data.waktu_pengerjaan || '';
                        document.getElementById('examModalTitle').textContent = 'Edit Ujian';
                    }
                    
                    modal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToastNotif('Error', 'Terjadi kesalahan saat mengambil data kuis/ujian', 'error');
                });
        }
        
        function deleteUjian(id, name = '') {
            openDeleteModal(id, 'ujian', name);
        }
        
        // Confirm delete action
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (!deleteItemId || !deleteItemType) return;
            
            let url;
            if (deleteItemType === 'modul') {
                url = `/admin/modul/${deleteItemId}`;
            } else if (deleteItemType === 'video') {
                url = `/admin/video/${deleteItemId}`;
            } else if (deleteItemType === 'materi') {
                url = `/admin/materi/${deleteItemId}`;
            } else if (deleteItemType === 'ujian') {
                url = `/admin/ujian/${deleteItemId}`;
            }
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeDeleteModal();
                    location.reload();
                } else {
                    showToastNotif('Gagal', 'Gagal menghapus item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToastNotif('Error', 'Terjadi kesalahan saat menghapus', 'error');
            });
        });
        
        // Close delete modal on overlay click
        document.getElementById('modalDelete').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        
        // ESC key handler for all modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const deleteModal = document.getElementById('modalDelete');
                const videoModal = document.getElementById('modalVideo');
                const pdfModal = document.getElementById('modalPdf');
                const quizModal = document.getElementById('modalQuiz');
                const examModal = document.getElementById('modalExam');
                
                if (deleteModal.classList.contains('active')) {
                    closeDeleteModal();
                } else if (videoModal.classList.contains('active')) {
                    closeVideoModal();
                } else if (pdfModal.classList.contains('active')) {
                    closePdfModal();
                } else if (quizModal.classList.contains('active')) {
                    closeQuizModal();
                } else if (examModal.classList.contains('active')) {
                    closeExamModal();
                }
            }
        });

        // ===================================
        // DRAG AND DROP FUNCTIONALITY
        // ===================================
        @hasanyrole('admin|super admin|pengajar')
        // Initialize Sortable for module list
        const weeksList = document.querySelector('.weeks-list');
        if (weeksList) {
            new Sortable(weeksList, {
                animation: 150,
                handle: '.week-header',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    updateModulOrder();
                }
            });
        }

        // Initialize Sortable for each materi list
        document.querySelectorAll('.materi-list').forEach(function(materiList) {
            new Sortable(materiList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                onEnd: function (evt) {
                    updateMateriOrder(materiList);
                }
            });
        });

        // Update module order via AJAX
        function updateModulOrder() {
            const modulElements = document.querySelectorAll('.week-item');
            const orders = [];
            
            modulElements.forEach((element, index) => {
                const modulId = element.id.replace('week-', '');
                orders.push({
                    id: parseInt(modulId),
                    urutan: index
                });
            });

            fetch('{{ route("admin.urutan.modul") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ orders: orders })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('✅ Urutan modul berhasil diperbarui');
                    showToast();
                } else {
                    showToastNotif('Gagal', 'Gagal memperbarui urutan modul', 'error');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToastNotif('Error', 'Terjadi kesalahan saat memperbarui urutan', 'error');
                location.reload();
            });
        }

        // Update materi order via AJAX
        function updateMateriOrder(materiList) {
            const materiElements = materiList.querySelectorAll('.materi-item');
            const videoOrders = [];
            const materiOrders = [];
            
            materiElements.forEach((element, index) => {
                const itemId = element.getAttribute('data-id');
                const itemType = element.getAttribute('data-type');
                
                if (!itemId) return; // Skip ujian/quiz
                
                if (itemType === 'video') {
                    videoOrders.push({
                        id: parseInt(itemId),
                        urutan: index
                    });
                } else if (itemType === 'pdf') {
                    materiOrders.push({
                        id: parseInt(itemId),
                        urutan: index
                    });
                }
            });

            // Update video orders
            if (videoOrders.length > 0) {
                fetch('{{ route("admin.urutan.video") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: videoOrders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('✅ Urutan video berhasil diperbarui');
                        showToast();
                    }
                })
                .catch(error => console.error('Error updating video order:', error));
            }

            // Update materi orders
            if (materiOrders.length > 0) {
                fetch('{{ route("admin.urutan.materi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ orders: materiOrders })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log('✅ Urutan materi berhasil diperbarui');
                        showToast();
                    }
                })
                .catch(error => console.error('Error updating materi order:', error));
            }
        }

        // Show toast notification
        function showToast() {
            const toast = document.getElementById('dragToast');
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.display = 'none';
            }, 2000);
        }
        @endhasanyrole

        // Initialize Quill editor
        let quillEditor;
        if (document.getElementById('quillEditor')) {
            quillEditor = new Quill('#quillEditor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                },
                placeholder: 'Tulis konten materi di sini...'
            });
            
            // Handle image upload
            quillEditor.getModule('toolbar').addHandler('image', function() {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();
                
                input.onchange = async function() {
                    const file = input.files[0];
                    if (file) {
                        const formData = new FormData();
                        formData.append('image', file);
                        
                        try {
                            const response = await fetch('{{ route("admin.materi.upload-image") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            });
                            const data = await response.json();
                            if (data.success) {
                                const range = quillEditor.getSelection();
                                quillEditor.insertEmbed(range.index, 'image', data.url);
                            } else {
                                showToastNotif('Gagal', 'Gagal upload gambar: ' + (data.message || 'Unknown error'), 'error');
                            }
                        } catch (error) {
                            showToastNotif('Error', 'Error upload gambar: ' + error, 'error');
                        }
                    }
                };
            });
        }


    </script>
    <script>
        // Prevent back navigation to video/materi/ujian pages - force back to pelatihan saya
        (function() {
            var pelatihanSayaUrl = '{{ auth()->user()->hasAnyRole(["admin", "super admin", "pengajar"]) ? route("admin.pelatihan.index") : route("user.pelatihan-saya.index") }}';
            
            // Push state to create barrier
            history.pushState(null, '', location.href);
            
            // Intercept back button
            window.onpopstate = function() {
                history.pushState(null, '', location.href);
                window.location.replace(pelatihanSayaUrl);
            };
        })();
    </script>
@endsection
