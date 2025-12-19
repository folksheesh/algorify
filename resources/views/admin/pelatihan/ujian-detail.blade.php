@extends('layouts.template')

@section('title', $ujian->judul)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin/ujian-detail.css') }}">
@endpush

@section('content')
@php
    $showResults = request()->has('show_results') && auth()->user()->hasRole('peserta');
    $userJawaban = collect();
    $hasil = null;
    
    if ($showResults) {
        // Get user answers
        $userJawaban = \App\Models\Jawaban::where('user_id', auth()->id())
            ->whereIn('soal_id', $ujian->soal->pluck('id'))
            ->get()
            ->keyBy('soal_id');
        
        // Get score
        $hasil = \App\Models\Nilai::where('user_id', auth()->id())
            ->where('ujian_id', $ujian->id)
            ->first();
    }
@endphp

@role('pengajar')
<div style="padding-top: 64px;">
    @include('components.topbar-pengajar')
@endrole

<div class="page-container">
    
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $ujian->modul->kursus_id) }}?open_modul={{ $ujian->modul_id }}" class="back-btn" onclick="navigateToModul(event, this.href)">
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

    @if($ujian->tipe === 'practice')
        <!-- Quiz Layout (dengan Sidebar Navigasi Materi) -->
        <div class="quiz-container" style="display: grid; @hasanyrole('admin|super admin|pengajar') grid-template-columns: 1fr 350px; @else grid-template-columns: 1fr; max-width: 900px; margin: 0 auto; @endhasanyrole gap: 1.5rem;">
            <div class="main-content">
                <!-- Quiz Header -->
                <div class="quiz-header">
                    <div class="quiz-meta">
                        <div class="quiz-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" fill="white"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" fill="white"/>
                            </svg>
                        </div>
                        <span style="font-size: 0.875rem; opacity: 0.9;">{{ $ujian->modul->judul }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <h1 class="quiz-title">{{ $ujian->judul }}</h1>
                        @hasanyrole('admin|super admin|pengajar')
                        <div style="display: flex; gap: 0.375rem; margin-left: auto;">
                            <button onclick="downloadTemplate()" class="btn btn-secondary" style="background: #10B981; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Template
                            </button>
                            <button onclick="openImportModal()" class="btn btn-secondary" style="background: #F59E0B; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Import
                            </button>
                            <button onclick="exportSoal()" class="btn btn-secondary" style="background: #3B82F6; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                                Export
                            </button>
                            <button onclick="openBankSoalModal()" class="btn btn-secondary" style="background: #8B5CF6; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" fill="currentColor"/>
                                </svg>
                                Bank Soal
                            </button>
                            <button onclick="openAddSoalModal()" class="btn btn-primary" style="padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Tambah Soal
                            </button>
                        </div>
                        @endhasanyrole
                    </div>
                </div>

                <!-- Quiz Body -->
                <div class="quiz-body">
                    @if($ujian->deskripsi)
                    <p class="description-text">{{ $ujian->deskripsi }}</p>
                    @endif

                    @if($showResults && $hasil)
                    <!-- Result Summary -->
                    <div style="background: {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? 'linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%)' : 'linear-gradient(135deg, #FEE2E2 0%, #FECACA 100%)' }}; border: 2px solid {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? '#10B981' : '#EF4444' }}; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            <div style="width: 60px; height: 60px; background: {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? '#10B981' : '#EF4444' }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                @if($hasil->nilai >= ($ujian->minimum_score ?? 70))
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                </svg>
                                @else
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="white">
                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                </svg>
                                @endif
                            </div>
                            <div style="flex: 1;">
                                <h3 style="font-size: 1.25rem; font-weight: 700; color: {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? '#065F46' : '#991B1B' }}; margin-bottom: 0.25rem;">
                                    {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? 'Selamat! Anda Lulus!' : 'Belum Lulus' }}
                                </h3>
                                <p style="font-size: 0.875rem; color: {{ $hasil->nilai >= ($ujian->minimum_score ?? 70) ? '#047857' : '#7F1D1D' }};">
                                    Nilai Anda: <strong>{{ number_format($hasil->nilai, 0) }}</strong> 
                                    (Minimum: {{ $ujian->minimum_score ?? 70 }})
                                </p>
                            </div>
                        </div>
                        @if($hasil->nilai < ($ujian->minimum_score ?? 70))
                        <p style="font-size: 0.875rem; color: #7F1D1D; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #FCA5A5;">
                            💡 Pelajari pembahasan di bawah dan coba lagi untuk meningkatkan pemahaman Anda!
                        </p>
                        @endif
                    </div>
                    @endif

                    @hasrole('peserta')
                    @if(!$showResults)
                    <!-- Start Quiz Screen -->
                    <div id="quizStartScreen" style="text-align: center; padding: 3rem 2rem;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="white">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1F2937; margin-bottom: 0.5rem;">{{ $ujian->judul }}</h2>
                        <p style="color: #6B7280; margin-bottom: 1.5rem;">{{ $ujian->deskripsi ?? 'Kuis ini berisi beberapa pertanyaan untuk menguji pemahaman Anda.' }}</p>
                        
                        <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 2rem; flex-wrap: wrap;">
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $ujian->soal->count() }}</div>
                                <div style="font-size: 0.875rem; color: #6B7280;">Pertanyaan</div>
                            </div>
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #10B981;">{{ $ujian->minimum_score ?? 70 }}</div>
                                <div style="font-size: 0.875rem; color: #6B7280;">Nilai Minimum</div>
                            </div>
                        </div>
                        
                        <button type="button" onclick="showStartQuizModal()" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 1rem 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 12px; font-size: 1rem; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4); transition: all 0.2s;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                            Mulai Kuis
                        </button>
                    </div>
                    @endif
                    @endhasrole

                    @if($ujian->soal->isEmpty())
                        <div style="padding: 2.5rem; text-align: center; color: #64748B; background: #F8FAFC; border: 1px dashed #CBD5F5; border-radius: 16px; margin-bottom: 2rem;">
                            Belum ada soal
                        </div>
                    @endif
                    <form id="quizForm" @hasrole('peserta') @if(!$showResults) style="display: none;" @endif @endhasrole>
                        @foreach($ujian->soal as $index => $soal)
                        <div class="question-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                                @hasanyrole('admin|super admin|pengajar')
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="button" onclick="editSoal({{ $soal->id }})" class="btn-edit-soal" title="Edit Soal">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deleteSoalConfirm({{ $soal->id }}, '{{ addslashes($soal->pertanyaan) }}')" class="btn-delete-soal" title="Hapus Soal">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                @endhasanyrole
                            </div>
                            <div class="question-text">{{ $soal->pertanyaan }}</div>
                            
                            @if($soal->lampiran_foto)
                            <div class="question-image" style="margin: 1rem 0;">
                                <img src="{{ Storage::url($soal->lampiran_foto) }}" alt="Lampiran Soal" style="max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #E5E7EB;">
                            </div>
                            @endif
                            
                            @if($soal->tipe_soal === 'single')
                            <div class="options-list">
                                @php
                                    $userAnswer = $showResults ? $userJawaban->get($soal->id) : null;
                                    $userAnswerId = $userAnswer ? $userAnswer->jawaban : null;
                                @endphp
                                @foreach($soal->pilihanJawaban as $pilihan)
                                @php
                                    $isCorrect = $pilihan->is_correct;
                                    $isUserAnswer = $showResults && $userAnswerId == $pilihan->id;
                                    $statusClass = '';
                                    if ($showResults) {
                                        if ($isCorrect) {
                                            $statusClass = 'correct-answer';
                                        } elseif ($isUserAnswer && !$isCorrect) {
                                            $statusClass = 'wrong-answer';
                                        }
                                    }
                                @endphp
                                <label class="option-item {{ $statusClass }}" style="{{ $showResults && !$statusClass ? 'opacity: 0.6;' : '' }}">
                                    <input type="radio" name="soal_{{ $soal->id }}" value="{{ $pilihan->id }}" class="option-radio" {{ $showResults ? 'disabled' : '' }} {{ $isUserAnswer ? 'checked' : '' }}>
                                    <span class="option-text">
                                        {{ $pilihan->pilihan }}
                                        @if($showResults && $isCorrect)
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#10B981" style="display: inline-block; margin-left: 0.5rem; vertical-align: middle;">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        @elseif($showResults && $isUserAnswer && !$isCorrect)
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#EF4444" style="display: inline-block; margin-left: 0.5rem; vertical-align: middle;">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                        </svg>
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="info-box-soal" style="margin-bottom: 1rem; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 0.75rem; border-radius: 8px; display: flex; gap: 0.5rem; align-items: center;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="#3B82F6">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span style="color: #1E40AF; font-size: 0.875rem;">Pilih lebih dari satu jawaban yang benar</span>
                            </div>
                            <div class="options-list">
                                @php
                                    $userAnswer = $showResults ? $userJawaban->get($soal->id) : null;
                                    $userAnswerIds = $userAnswer ? json_decode($userAnswer->jawaban, true) : [];
                                    if (!is_array($userAnswerIds)) $userAnswerIds = [];
                                    $userAnswerIds = array_map('strval', $userAnswerIds);
                                @endphp
                                @foreach($soal->pilihanJawaban as $pilihan)
                                @php
                                    $isCorrect = $pilihan->is_correct;
                                    $isUserAnswer = $showResults && in_array((string)$pilihan->id, $userAnswerIds);
                                    $statusClass = '';
                                    if ($showResults) {
                                        if ($isCorrect) {
                                            $statusClass = 'correct-answer';
                                        } elseif ($isUserAnswer && !$isCorrect) {
                                            $statusClass = 'wrong-answer';
                                        }
                                    }
                                @endphp
                                <label class="option-item {{ $statusClass }}" style="{{ $showResults && !$statusClass ? 'opacity: 0.6;' : '' }}">
                                    <input type="checkbox" name="soal_{{ $soal->id }}[]" value="{{ $pilihan->id }}" class="option-checkbox" {{ $showResults ? 'disabled' : '' }} {{ $isUserAnswer ? 'checked' : '' }}>
                                    <span class="option-text">
                                        {{ $pilihan->pilihan }}
                                        @if($showResults && $isCorrect)
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#10B981" style="display: inline-block; margin-left: 0.5rem; vertical-align: middle;">
                                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                        </svg>
                                        @elseif($showResults && $isUserAnswer && !$isCorrect)
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#EF4444" style="display: inline-block; margin-left: 0.5rem; vertical-align: middle;">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                        </svg>
                                        @endif
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @endif
                            
                            @if($showResults && $soal->pembahasan)
                            <!-- Pembahasan -->
                            <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#F59E0B">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                    </svg>
                                    <strong style="color: #92400E; font-size: 0.875rem;">Pembahasan:</strong>
                                </div>
                                <p style="color: #78350F; font-size: 0.875rem; line-height: 1.6; margin: 0;">{{ $soal->pembahasan }}</p>
                            </div>
                            @endif
                        </div>
                        @endforeach

                        <div class="action-buttons">
                            @if($showResults)
                                @if($hasil && $hasil->nilai >= ($ujian->minimum_score ?? 70))
                                    {{-- Sudah Lulus - Tombol Selesai ke halaman pelatihan --}}
                                    <a href="{{ route('admin.pelatihan.show', $ujian->modul->kursus_id) }}" class="btn btn-success" style="text-decoration: none;">
                                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Selesai
                                    </a>
                                @else
                                    {{-- Belum Lulus - Tombol Coba Lagi --}}
                                    <a href="{{ route('admin.ujian.show', $ujian->id) }}" class="btn btn-primary" style="text-decoration: none;">
                                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                        </svg>
                                        Coba Lagi
                                    </a>
                                @endif
                            @else
                                @hasrole('peserta')
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                                    <button type="submit" class="btn btn-success">
                                        <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Selesai
                                    </button>
                                @endhasrole
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar Navigasi Materi untuk Quiz - Hidden for peserta -->
            @hasanyrole('admin|super admin|pengajar')
            <div class="quiz-sidebar">
                <!-- Module Info -->
                <div class="sidebar-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); padding: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">Informasi Modul</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.75rem; color: #6B7280; margin-bottom: 0.25rem;">Modul</div>
                            <div style="font-weight: 600; color: #1F2937;">{{ $ujian->modul->judul ?? '-' }}</div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.75rem; color: #6B7280; margin-bottom: 0.25rem;">Kursus</div>
                            <div style="font-weight: 600; color: #1F2937;">{{ $ujian->modul->kursus->judul ?? '-' }}</div>
                        </div>
                        
                        <div>
                            <div style="font-size: 0.75rem; color: #6B7280; margin-bottom: 0.25rem;">Pengajar</div>
                            <div style="font-weight: 600; color: #1F2937;">{{ $ujian->modul->kursus->pengajar->name ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Related Materials -->
                <div class="sidebar-card" style="background: white; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); padding: 1.5rem;">
                    <h3 style="font-size: 1rem; font-weight: 700; color: #1F2937; margin-bottom: 1rem;">Materi Lainnya</h3>
                    
                    <div class="materials-list" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($allItems as $item)
                            @php
                                $itemType = $item['type'];
                                $itemData = $item['data'];
                                $isCurrent = ($itemType === 'quiz' && $itemData->id === $ujian->id);
                                
                                // Determine route based on type
                                if ($itemType === 'video') {
                                    $routeName = 'admin.video.show';
                                } elseif ($itemType === 'bacaan') {
                                    $routeName = 'admin.materi.show';
                                } else {
                                    $routeName = 'admin.ujian.show';
                                }
                                
                                // Determine label
                                $typeLabel = match($itemType) {
                                    'video' => 'Video',
                                    'bacaan' => 'Bacaan',
                                    'quiz' => 'Quiz',
                                    'ujian' => 'Ujian',
                                    default => 'Materi'
                                };
                            @endphp
                            
                            <a href="{{ route($routeName, $itemData->id) }}" class="material-item" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem; border-radius: 8px; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; {{ $isCurrent ? 'background: #ECFDF5; border-color: #10B981;' : '' }}" onmouseover="this.style.background='{{ $isCurrent ? '#ECFDF5' : '#F9FAFB' }}'" onmouseout="this.style.background='{{ $isCurrent ? '#ECFDF5' : '' }}'">
                                
                                @if($item['completed'] ?? false)
                                    {{-- Icon Centang Hijau untuk item yang sudah selesai --}}
                                    <div style="width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: #D1FAE5;">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="#10B981">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @else
                                    <div style="width: 32px; height: 32px; border-radius: 6px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: {{ $itemType === 'video' ? ($isCurrent ? '#667eea' : '#EEF2FF') : ($itemType === 'bacaan' ? ($isCurrent ? '#EF4444' : '#FEF2F2') : ($itemType === 'quiz' ? ($isCurrent ? '#10B981' : '#ECFDF5') : ($isCurrent ? '#F59E0B' : '#FEF3C7'))) }};">
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
                                
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.875rem; font-weight: 600; color: {{ $isCurrent ? '#10B981' : '#1F2937' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $itemData->judul }}</div>
                                    <div style="font-size: 0.75rem; color: {{ ($item['completed'] ?? false) ? '#10B981' : '#6B7280' }};">
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
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="#10B981" style="flex-shrink: 0;">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endhasanyrole
        </div>
    @else
        {{-- ======================================== --}}
        {{-- EXAM LAYOUT BARU (Seperti Gambar Referensi) --}}
        {{-- ======================================== --}}
        
        {{-- Exam Navbar (Header dengan Progress & Timer) - Hidden saat belum mulai --}}
        <div id="examNavbar" class="exam-navbar" style="display: none;">
            <div class="exam-navbar-inner">
                <div class="exam-navbar-left">
                    <span class="exam-label">Ujian</span>
                </div>
                <div class="exam-navbar-center">
                    <div class="exam-header-card">
                        <div class="exam-header-top">
                            <h2 class="exam-header-title">{{ $ujian->judul }}</h2>
                            <div class="exam-header-timer" id="examTimerDisplay">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="color: #EF4444;">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span id="timerNavbar">60:00</span>
                            </div>
                        </div>
                        <div class="exam-progress-info">
                            <span id="questionProgress">Pertanyaan 1 dari {{ $ujian->soal->count() }}</span>
                        </div>
                        <div class="exam-progress-bar">
                            <div class="exam-progress-fill" id="progressBar" style="width: {{ 100 / max($ujian->soal->count(), 1) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Exam Layout (Dengan Navigasi & Timer) -->
        <div class="exam-container @hasanyrole('admin|super admin|pengajar')exam-active @endhasanyrole" id="examContainer">
            <!-- Main Content -->
            <div class="main-content" id="examMainContent">
                <!-- Exam Header (untuk Admin/Pengajar) -->
                @hasanyrole('admin|super admin|pengajar')
                <div class="quiz-header">
                    <div class="quiz-meta">
                        <div class="quiz-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" fill="white"/>
                            </svg>
                        </div>
                        <span style="font-size: 0.875rem; opacity: 0.9;">{{ $ujian->modul->judul }}</span>
                    </div>
                    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                        <h1 class="quiz-title">{{ $ujian->judul }}</h1>
                        <div style="display: flex; gap: 0.375rem; margin-left: auto;">
                            <button onclick="downloadTemplate()" class="btn btn-secondary" style="background: #10B981; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;" title="Download Template Excel">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Template
                            </button>
                            <button onclick="openImportModal()" class="btn btn-secondary" style="background: #F59E0B; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;" title="Import Soal dari Excel">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Import
                            </button>
                            <button onclick="exportSoal()" class="btn btn-secondary" style="background: #3B82F6; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;" title="Export Soal ke Excel">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                                    <path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                </svg>
                                Export
                            </button>
                            <button onclick="openBankSoalModal()" class="btn btn-secondary" style="background: #8B5CF6; color: white; border: none; padding: 0.5rem 0.875rem; font-size: 0.75rem;" title="Tambah dari Bank Soal">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" fill="currentColor"/>
                                </svg>
                                Bank Soal
                            </button>
                            <button onclick="openAddSoalModal()" class="btn btn-primary" style="color: white; padding: 0.5rem 0.875rem; font-size: 0.75rem;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                                </svg>
                                Tambah Soal
                            </button>
                        </div>
                    </div>
                </div>
                @endhasanyrole

                <!-- Exam Body -->
                <div class="quiz-body">
                    @if($ujian->deskripsi)
                    @hasanyrole('admin|super admin|pengajar')
                    <p class="description-text">{{ $ujian->deskripsi }}</p>
                    @endhasanyrole
                    @endif

                    <!-- Start Screen untuk Ujian (Exam) - Hanya Peserta -->
                    @hasrole('peserta')
                    <div id="examStartScreen" class="start-screen" style="text-align: center; padding: 3rem 2rem; background: linear-gradient(135deg, #EEF2FF 0%, #E0E7FF 100%); border-radius: 12px; margin-bottom: 1.5rem;">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center;">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="white">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h2 style="font-size: 1.5rem; font-weight: 700; color: #1E293B; margin-bottom: 0.75rem;">Siap Mengerjakan Ujian?</h2>
                        <p style="color: #64748B; margin-bottom: 1.5rem; font-size: 0.95rem;">Pastikan Anda sudah siap sebelum memulai ujian. Waktu akan mulai berjalan setelah Anda memulai.</p>
                        
                        <div class="start-info" style="display: inline-flex; gap: 2rem; background: white; padding: 1rem 2rem; border-radius: 8px; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">{{ $ujian->soal->count() }}</div>
                                <div style="font-size: 0.75rem; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Jumlah Soal</div>
                            </div>
                            <div style="width: 1px; background: #E5E7EB;"></div>
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #10B981;">{{ $ujian->minimum_score ?? 70 }}</div>
                                <div style="font-size: 0.75rem; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Nilai Minimum</div>
                            </div>
                            <div style="width: 1px; background: #E5E7EB;"></div>
                            <div style="text-align: center;">
                                <div style="font-size: 1.5rem; font-weight: 700; color: #F59E0B;">60</div>
                                <div style="font-size: 0.75rem; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Menit</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem; padding: 1rem; background: #FEF3C7; border-radius: 8px; border-left: 4px solid #F59E0B;">
                            <p style="color: #92400E; font-size: 0.875rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="#F59E0B">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <strong>Perhatian:</strong> Ujian akan dimulai setelah Anda menekan tombol di bawah. Pastikan koneksi internet stabil.
                            </p>
                        </div>
                        
                        <button onclick="startExam()" class="btn btn-primary" style="padding: 0.875rem 2rem; font-size: 1rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                            </svg>
                            Mulai Ujian
                        </button>
                    </div>
                    @endrole

                    <div id="examFormContainer" @hasrole('peserta') style="display: none;" @endhasrole>
                    <form id="examForm">
                        @foreach($ujian->soal as $index => $soal)
                        <div class="question-card" id="question-{{ $index + 1 }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                                @hasanyrole('admin|super admin|pengajar')
                                <div style="display: flex; gap: 0.5rem;">
                                    <button type="button" onclick="editSoal({{ $soal->id }})" class="btn-edit-soal" title="Edit Soal">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deleteSoalConfirm({{ $soal->id }}, '{{ addslashes($soal->pertanyaan) }}')" class="btn-delete-soal" title="Hapus Soal">
                                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                                @endhasanyrole
                            </div>
                            <div class="question-text">{{ $soal->pertanyaan }}</div>
                            
                            @if($soal->lampiran_foto)
                            <div class="question-image" style="margin: 1rem 0;">
                                <img src="{{ Storage::url($soal->lampiran_foto) }}" alt="Lampiran Soal" style="max-width: 100%; max-height: 400px; border-radius: 8px; border: 1px solid #E5E7EB;">
                            </div>
                            @endif
                            
                            @if($soal->tipe_soal === 'single')
                            <div class="options-list">
                                @foreach($soal->pilihanJawaban as $pilihan)
                                <label class="option-item" data-question="{{ $index + 1 }}">
                                    <input type="radio" name="soal_{{ $soal->id }}" value="{{ $pilihan->id }}" class="option-radio" onchange="markAsAnswered({{ $index + 1 }})">
                                    <span class="option-text">{{ $pilihan->pilihan }}</span>
                                </label>
                                @endforeach
                            </div>
                            @else
                            <div class="info-box-soal" style="margin-bottom: 1rem; background: #EFF6FF; border: 1px solid #BFDBFE; padding: 0.75rem; border-radius: 8px; display: flex; gap: 0.5rem; align-items: center;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="#3B82F6">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span style="color: #1E40AF; font-size: 0.875rem;">Pilih lebih dari satu jawaban yang benar</span>
                            </div>
                            <div class="options-list">
                                @foreach($soal->pilihanJawaban as $pilihan)
                                <label class="option-item" data-question="{{ $index + 1 }}">
                                    <input type="checkbox" name="soal_{{ $soal->id }}[]" value="{{ $pilihan->id }}" class="option-checkbox" onchange="markAsAnswered({{ $index + 1 }})">
                                    <span class="option-text">{{ $pilihan->pilihan }}</span>
                                </label>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach

                        {{-- Navigation Buttons (seperti gambar referensi) --}}
                        <div class="exam-navigation" id="navigation-buttons">
                            <button type="button" class="exam-nav-btn secondary" id="prevBtn" onclick="navigateQuestion(-1)" style="display: none;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sebelumnya
                            </button>
                            <div style="flex: 1;"></div>
                            <button type="button" class="exam-nav-btn primary" id="nextBtn" onclick="navigateQuestion(1)">
                                Selanjutnya
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="submit" class="exam-nav-btn primary" id="submitBtn" style="display: none;">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Selesai Ujian
                            </button>
                        </div>
                    </form>
                    </div><!-- End examFormContainer -->
                </div>
            </div>

            <!-- Sidebar Navigation (seperti gambar referensi) -->
            <div id="examSidebar" class="exam-sidebar" @hasrole('peserta') style="display: none;" @endhasrole>
                <!-- Timer Card -->
                <div class="exam-sidebar-card">
                    <div class="timer-label" style="font-size: 0.875rem; color: #64748B; margin-bottom: 0.5rem; text-align: center;">Sisa Waktu</div>
                    <div class="timer-display" id="timer" style="font-size: 2rem; font-weight: 700; color: #6366f1; text-align: center;">60:00</div>
                </div>

                <!-- Navigation Card -->
                <div class="exam-sidebar-card exam-sidebar-nav">
                    <h3 class="nav-title" style="font-size: 1rem; font-weight: 600; color: #1E293B; margin-bottom: 1rem; text-align: center;">Navigasi Soal</h3>
                    
                    <div class="question-grid">
                        @for($i = 1; $i <= $ujian->soal->count(); $i++)
                        <button type="button" class="question-nav-btn {{ $i === 1 ? 'active' : '' }}" id="nav-{{ $i }}" onclick="goToQuestion({{ $i }})">
                            {{ $i }}
                        </button>
                        @endfor
                    </div>

                    <div class="legend">
                        <div class="legend-item">
                            <div class="legend-box current"></div>
                            <span>Soal Sekarang</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box answered"></div>
                            <span>Sudah Dijawab</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-box unanswered"></div>
                            <span>Belum Dijawab</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- Modal Konfirmasi Hapus Soal --}}
<style>
    @keyframes deleteSoalModalFadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes deleteSoalModalFadeOut { from { opacity: 1; } to { opacity: 0; } }
    @keyframes deleteSoalModalSlideIn { from { opacity: 0; transform: scale(0.9) translateY(-20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes deleteSoalModalSlideOut { from { opacity: 1; transform: scale(1) translateY(0); } to { opacity: 0; transform: scale(0.9) translateY(-20px); } }
    #deleteSoalModal { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; }
    #deleteSoalModal.active { display: flex; animation: deleteSoalModalFadeIn 0.2s ease-out forwards; }
    #deleteSoalModal.closing { animation: deleteSoalModalFadeOut 0.2s ease-out forwards; }
    #deleteSoalModal .delete-modal-box { background: white; border-radius: 16px; max-width: 400px; width: 90%; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; animation: deleteSoalModalSlideIn 0.3s ease-out forwards; }
    #deleteSoalModal.closing .delete-modal-box { animation: deleteSoalModalSlideOut 0.2s ease-out forwards; }
</style>
<div id="deleteSoalModal">
    <div class="delete-modal-box">
        <button onclick="closeDeleteSoalModal()" style="position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748B; line-height: 1;">&times;</button>
        <div style="text-align: center;">
            <div style="width: 60px; height: 60px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
                </svg>
            </div>
            <h2 style="color: #1E293B; margin: 0 0 0.5rem; font-size: 1.25rem; font-weight: 600;">Konfirmasi Hapus</h2>
            <p id="deleteSoalText" style="color: #64748B; font-size: 0.875rem; margin: 0 0 1.5rem;">Apakah Anda yakin ingin menghapus soal ini?</p>
            <div style="display: flex; justify-content: center; gap: 1rem;">
                <button type="button" onclick="closeDeleteSoalModal()" style="padding: 0.625rem 1.5rem; border-radius: 8px; font-weight: 500; background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; cursor: pointer;">Batal</button>
                <button type="button" onclick="confirmDeleteSoal()" style="background: #DC2626; color: white; padding: 0.625rem 1.5rem; border-radius: 8px; font-weight: 500; border: none; cursor: pointer;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- TOAST NOTIFICATION - Notifikasi di pojok kanan atas --}}
<div id="toastNotification" class="toast-notification">
    <div class="toast-icon-wrapper" id="toastIconWrapper">
        <svg class="toast-icon" id="toastIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>
    <div class="toast-content">
        <div class="toast-title" id="toastTitle">Notifikasi</div>
        <div class="toast-message" id="toastMessage">Pesan notifikasi</div>
    </div>
    <button class="toast-close" onclick="closeToast()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M18 6L6 18M6 6l12 12"/>
        </svg>
    </button>
</div>

<style>
/* ======================================== */
/* EXAM NAVBAR STYLES (Seperti Gambar Referensi) */
/* ======================================== */
.exam-navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
    z-index: 1000;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
}

.exam-navbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1.5rem;
    max-width: 100%;
}

.exam-navbar-left {
    flex-shrink: 0;
}

.exam-label {
    color: rgba(255,255,255,0.7);
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.exam-navbar-center {
    flex: 1;
    max-width: 900px;
    margin: 0 auto;
}

.exam-header-card {
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.exam-header-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.exam-header-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #1E293B;
    margin: 0;
}

.exam-header-timer {
    display: flex;
    align-items: center;
    gap: 0.375rem;
    background: #FEE2E2;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-weight: 700;
    color: #DC2626;
    font-size: 0.875rem;
}

.exam-progress-info {
    font-size: 0.8125rem;
    color: #64748B;
    margin-bottom: 0.5rem;
}

.exam-progress-bar {
    height: 6px;
    background: #E2E8F0;
    border-radius: 3px;
    overflow: hidden;
}

.exam-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4338ca 0%, #6366f1 100%);
    border-radius: 3px;
    transition: width 0.3s ease;
}

/* Exam Layout Active Mode */
body.exam-active-mode {
    padding-top: 110px;
}

body.exam-active-mode .page-container {
    padding-top: 0;
}

body.exam-active-mode .back-btn {
    display: none;
}

body.exam-active-mode .exam-container {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

body.exam-active-mode .main-content {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

body.exam-active-mode #examSidebar {
    display: block !important;
    position: sticky;
    top: 130px;
    height: fit-content;
}

/* Question Card dalam Exam Mode */
.exam-question-wrapper {
    padding: 2rem;
}

.exam-question-badge {
    display: inline-block;
    background: #EEF2FF;
    color: #4338ca;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.exam-question-text {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1E293B;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.exam-options-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.exam-option-item {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    background: #F8FAFC;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.exam-option-item:hover {
    border-color: #6366f1;
    background: #EEF2FF;
}

.exam-option-item.selected {
    border-color: #6366f1;
    background: #EEF2FF;
}

/* Navigation Buttons */
.exam-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-top: 1px solid #E2E8F0;
    background: #F8FAFC;
}

.exam-nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}

.exam-nav-btn.secondary {
    background: white;
    color: #64748B;
    border: 2px solid #E2E8F0;
}

.exam-nav-btn.secondary:hover {
    background: #F1F5F9;
    border-color: #CBD5E1;
}

.exam-nav-btn.primary {
    background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
    color: white;
}

.exam-nav-btn.primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
}

.exam-nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Sidebar Navigation in Exam */
.exam-sidebar-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
    margin-bottom: 1rem;
}

.exam-sidebar-title {
    font-size: 1rem;
    font-weight: 700;
    color: #1E293B;
    margin-bottom: 1rem;
}

.exam-question-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 0.5rem;
    margin-bottom: 1.25rem;
}

.exam-nav-number {
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F1F5F9;
    border: 2px solid #E2E8F0;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    color: #64748B;
    cursor: pointer;
    transition: all 0.2s;
}

.exam-nav-number:hover {
    border-color: #6366f1;
}

.exam-nav-number.current {
    background: #6366f1;
    border-color: #6366f1;
    color: white;
}

.exam-nav-number.answered {
    background: #10B981;
    border-color: #10B981;
    color: white;
}

.exam-legend {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    padding-top: 1rem;
    border-top: 1px solid #E2E8F0;
}

.exam-legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #64748B;
}

.exam-legend-box {
    width: 18px;
    height: 18px;
    border-radius: 4px;
}

.exam-legend-box.current {
    background: #6366f1;
}

.exam-legend-box.answered {
    background: #10B981;
}

.exam-legend-box.unanswered {
    background: #F1F5F9;
    border: 2px solid #E2E8F0;
}

/* Responsive */
@media (max-width: 1024px) {
    body.exam-active-mode .exam-container {
        grid-template-columns: 1fr;
    }
    
    body.exam-active-mode #examSidebar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        top: auto;
        z-index: 999;
        display: flex;
        gap: 0;
        padding: 1rem;
        background: white;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 0 0;
    }
    
    .exam-sidebar-card {
        margin-bottom: 0;
    }
    
    .timer-card {
        display: none;
    }
    
    body.exam-active-mode {
        padding-bottom: 200px;
    }
}

@media (max-width: 768px) {
    .exam-navbar-inner {
        padding: 0.5rem 1rem;
    }
    
    .exam-header-card {
        padding: 0.75rem 1rem;
    }
    
    .exam-header-title {
        font-size: 0.95rem;
    }
    
    .exam-question-wrapper {
        padding: 1.25rem;
    }
}

/* Toast Notification Styles */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 1rem 1.25rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    z-index: 10001;
    display: none;
    align-items: center;
    gap: 0.75rem;
    min-width: 320px;
    max-width: 450px;
    animation: toastSlideIn 0.3s ease;
    border-left: 4px solid #10B981;
}

.toast-notification.active {
    display: flex;
}

.toast-notification.success {
    border-left-color: #10B981;
}

.toast-notification.error {
    border-left-color: #EF4444;
}

.toast-notification.warning {
    border-left-color: #F59E0B;
}

.toast-notification.info {
    border-left-color: #3B82F6;
}

.toast-icon-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.toast-notification.success .toast-icon-wrapper {
    background: #D1FAE5;
    color: #10B981;
}

.toast-notification.error .toast-icon-wrapper {
    background: #FEE2E2;
    color: #EF4444;
}

.toast-notification.warning .toast-icon-wrapper {
    background: #FEF3C7;
    color: #F59E0B;
}

.toast-notification.info .toast-icon-wrapper {
    background: #DBEAFE;
    color: #3B82F6;
}

.toast-icon {
    width: 20px;
    height: 20px;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 600;
    font-size: 0.875rem;
    color: #1E293B;
    margin-bottom: 0.125rem;
}

.toast-message {
    font-size: 0.8125rem;
    color: #64748B;
    line-height: 1.4;
}

.toast-close {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 0.25rem;
    color: #94A3B8;
    border-radius: 4px;
    transition: all 0.2s;
}

.toast-close:hover {
    background: #F1F5F9;
    color: #64748B;
}

@keyframes toastSlideIn {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes toastSlideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100px);
    }
}
</style>

<script>
    // Toast Notification Function
    let toastTimeout = null;
    
    function showToast(title, message, type = 'success') {
        const toast = document.getElementById('toastNotification');
        const toastTitle = document.getElementById('toastTitle');
        const toastMessage = document.getElementById('toastMessage');
        const toastIconWrapper = document.getElementById('toastIconWrapper');
        
        // Clear existing timeout
        if (toastTimeout) {
            clearTimeout(toastTimeout);
        }
        
        // Set content
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        
        // Set type class
        toast.className = 'toast-notification ' + type + ' active';
        
        // Set icon based on type
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
        
        // Auto hide after 4 seconds
        toastTimeout = setTimeout(() => {
            closeToast();
        }, 4000);
    }
    
    function closeToast() {
        const toast = document.getElementById('toastNotification');
        toast.style.animation = 'toastSlideOut 0.3s ease forwards';
        setTimeout(() => {
            toast.classList.remove('active');
            toast.style.animation = '';
        }, 300);
    }

    // Radio button selection highlighting
    document.querySelectorAll('.option-item').forEach(item => {
        item.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            const checkbox = this.querySelector('input[type="checkbox"]');
            const questionNum = this.dataset.question;
            
            if (radio) {
                // Radio button logic (single choice)
                // Remove selected class from all options in this question
                if (questionNum) {
                    document.querySelectorAll(`.option-item[data-question="${questionNum}"]`).forEach(opt => {
                        opt.classList.remove('selected');
                    });
                } else {
                    // For quiz (simple layout)
                    const parent = this.closest('.question-card');
                    parent.querySelectorAll('.option-item').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                }
                
                // Add selected class to clicked option
                this.classList.add('selected');
                radio.checked = true;
                
                // Mark as answered for navigation (fix for single choice)
                if (questionNum && typeof markAsAnswered === 'function') {
                    markAsAnswered(parseInt(questionNum));
                }
            } else if (checkbox) {
                // Checkbox logic (multiple choice)
                checkbox.checked = !checkbox.checked;
                if (checkbox.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
                
                // Mark as answered for navigation (fix for multiple choice)
                if (questionNum && typeof markAsAnswered === 'function') {
                    markAsAnswered(parseInt(questionNum));
                }
            }
        });
    });

    // Function to start quiz (show form and hide start screen)
    function showStartQuizModal() {
        // Hide start screen by ID
        const startScreen = document.getElementById('quizStartScreen');
        if (startScreen) {
            startScreen.style.display = 'none';
        }
        
        // Show quiz form
        const quizForm = document.getElementById('quizForm');
        if (quizForm) {
            quizForm.style.display = 'block';
        }
    }

    // Bank Soal Variables
    let bankSoalData = [];
    let filteredBankSoal = [];
    let selectedSoalIds = new Set();
    const kursusKategori = "{{ $ujian->modul->kursus->kategori ?? '' }}";
    const ujianId = {{ $ujian->id }};

    // Function to open Bank Soal modal
    function openBankSoalModal() {
        document.getElementById('modalBankSoal').classList.add('active');
        loadBankSoalData();
    }

    // Function to close Bank Soal modal
    function closeBankSoalModal() {
        document.getElementById('modalBankSoal').classList.remove('active');
        selectedSoalIds.clear();
        updateSelectedCount();
    }

    // Load Bank Soal data from server
    async function loadBankSoalData() {
        const listContainer = document.getElementById('bankSoalList');
        listContainer.innerHTML = `
            <div style="text-align: center; padding: 2rem; color: #64748B;">
                <svg width="48" height="48" viewBox="0 0 20 20" fill="#CBD5E1" style="margin: 0 auto 1rem; animation: spin 1s linear infinite;">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                </svg>
                Memuat soal dari bank soal...
            </div>
        `;

        try {
            // Build query with kategori filter
            const params = new URLSearchParams();
            if (kursusKategori) {
                params.append('kategori_nama', kursusKategori);
            }
            
            const response = await fetch(`{{ route('admin.bank-soal.data') }}?${params.toString()}`);
            const result = await response.json();
            
            bankSoalData = result.data || [];
            filteredBankSoal = [...bankSoalData];
            
            renderBankSoalList();
        } catch (error) {
            console.error('Error loading bank soal:', error);
            listContainer.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #EF4444;">
                    <svg width="48" height="48" viewBox="0 0 20 20" fill="#FCA5A5" style="margin: 0 auto 1rem;">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    Gagal memuat data. Silakan coba lagi.
                </div>
            `;
        }
    }

    // Filter Bank Soal
    function filterBankSoal() {
        const search = document.getElementById('bankSoalSearch').value.toLowerCase();
        const tipe = document.getElementById('bankSoalTipe').value;
        
        filteredBankSoal = bankSoalData.filter(item => {
            const matchSearch = !search || item.pertanyaan.toLowerCase().includes(search);
            const matchTipe = !tipe || item.tipe_soal === tipe;
            return matchSearch && matchTipe;
        });
        
        renderBankSoalList();
    }

    // Render Bank Soal list
    function renderBankSoalList() {
        const listContainer = document.getElementById('bankSoalList');
        
        if (filteredBankSoal.length === 0) {
            listContainer.innerHTML = `
                <div style="text-align: center; padding: 2rem; color: #64748B;">
                    <svg width="48" height="48" viewBox="0 0 20 20" fill="#CBD5E1" style="margin: 0 auto 1rem;">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    <p style="margin: 0;">Tidak ada soal ditemukan untuk kategori ini</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.8125rem;">Silakan tambah soal baru di halaman Bank Soal</p>
                </div>
            `;
            return;
        }

        listContainer.innerHTML = filteredBankSoal.map(item => {
            const isSelected = selectedSoalIds.has(item.id);
            const tipeBadge = item.tipe_soal === 'pilihan_ganda' 
                ? '<span style="background: #DBEAFE; color: #1E40AF; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">Pilihan Ganda</span>'
                : '<span style="background: #FEF3C7; color: #92400E; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500;">Multi Jawaban</span>';
            
            return `
                <div class="bank-soal-item ${isSelected ? 'selected' : ''}" 
                     onclick="toggleSoalSelection(${item.id})"
                     style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border: 2px solid ${isSelected ? '#8B5CF6' : '#E2E8F0'}; border-radius: 10px; cursor: pointer; transition: all 0.2s; background: ${isSelected ? '#F5F3FF' : 'white'};">
                    <div style="flex-shrink: 0; width: 24px; height: 24px; border: 2px solid ${isSelected ? '#8B5CF6' : '#CBD5E1'}; border-radius: 6px; display: flex; align-items: center; justify-content: center; background: ${isSelected ? '#8B5CF6' : 'white'}; transition: all 0.2s;">
                        ${isSelected ? '<svg width="14" height="14" viewBox="0 0 20 20" fill="white"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>' : ''}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            ${tipeBadge}
                            <span style="font-size: 0.75rem; color: #64748B;">${item.poin} Poin</span>
                        </div>
                        <p style="margin: 0; font-size: 0.875rem; color: #1E293B; line-height: 1.5;">${item.pertanyaan.length > 150 ? item.pertanyaan.substring(0, 150) + '...' : item.pertanyaan}</p>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Toggle soal selection
    function toggleSoalSelection(id) {
        if (selectedSoalIds.has(id)) {
            selectedSoalIds.delete(id);
        } else {
            selectedSoalIds.add(id);
        }
        renderBankSoalList();
        updateSelectedCount();
    }

    // Update selected count
    function updateSelectedCount() {
        document.getElementById('selectedCount').textContent = selectedSoalIds.size;
    }

    // Add selected soal to ujian
    async function addSelectedSoal() {
        if (selectedSoalIds.size === 0) {
            showToast('Peringatan', 'Pilih minimal satu soal untuk ditambahkan', 'warning');
            return;
        }

        const submitBtn = document.querySelector('#modalBankSoal .btn-submit-soal');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="animation: spin 1s linear infinite;">
                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
            </svg>
            Menambahkan...
        `;
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("admin.soal.add-from-bank") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    ujian_id: ujianId,
                    bank_soal_ids: Array.from(selectedSoalIds)
                })
            });

            const result = await response.json();

            if (result.success) {
                showToast('Berhasil', `${selectedSoalIds.size} soal berhasil ditambahkan ke {{ $ujian->tipe === 'practice' ? 'kuis' : 'ujian' }}`, 'success');
                closeBankSoalModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Gagal', result.message || 'Gagal menambahkan soal', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menambahkan soal', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    @if($ujian->tipe === 'exam')
    // Exam specific JavaScript
    let currentQuestion = 1;
    const totalQuestions = {{ $ujian->soal->count() }};
    let timeLeft = 60 * 60; // 60 minutes in seconds

    // Timer
    function startTimer() {
        const timerDisplay = document.getElementById('timer');
        const timerNavbar = document.getElementById('timerNavbar');
        
        setInterval(() => {
            if (timeLeft <= 0) {
                document.getElementById('examForm').submit();
                return;
            }
            
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Update both timer displays
            if (timerDisplay) timerDisplay.textContent = timeString;
            if (timerNavbar) timerNavbar.textContent = timeString;
            
            // Change color based on time left
            if (timeLeft <= 300) { // 5 minutes
                if (timerDisplay) {
                    timerDisplay.classList.add('danger');
                    timerDisplay.classList.remove('warning');
                }
            } else if (timeLeft <= 600) { // 10 minutes
                if (timerDisplay) timerDisplay.classList.add('warning');
            }
        }, 1000);
    }

    // Navigation
    function goToQuestion(num) {
        // Hide all questions
        for (let i = 1; i <= totalQuestions; i++) {
            document.getElementById(`question-${i}`).style.display = 'none';
            const navBtn = document.getElementById(`nav-${i}`);
            // Only remove active if it's not answered
            if (!navBtn.classList.contains('answered')) {
                navBtn.classList.remove('active');
            }
        }
        
        // Show selected question
        document.getElementById(`question-${num}`).style.display = 'block';
        const currentNavBtn = document.getElementById(`nav-${num}`);
        // Only add active if it's not already answered
        if (!currentNavBtn.classList.contains('answered')) {
            currentNavBtn.classList.add('active');
        }
        currentQuestion = num;
        
        // Update navigation buttons
        updateNavigationButtons();
        
        // Update progress bar
        if (typeof updateProgressBar === 'function') {
            updateProgressBar(num);
        }
    }

    function navigateQuestion(direction) {
        const newQuestion = currentQuestion + direction;
        if (newQuestion >= 1 && newQuestion <= totalQuestions) {
            goToQuestion(newQuestion);
        }
    }

    function updateNavigationButtons() {
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        
        prevBtn.style.display = currentQuestion === 1 ? 'none' : 'inline-flex';
        nextBtn.style.display = currentQuestion === totalQuestions ? 'none' : 'inline-flex';
        submitBtn.style.display = currentQuestion === totalQuestions ? 'inline-flex' : 'none';
    }

    function markAsAnswered(questionNum) {
        const navBtn = document.getElementById(`nav-${questionNum}`);
        if (!navBtn) return;
        
        // Check if question is answered by checking both radio and checkbox inputs
        const questionCard = document.getElementById(`question-${questionNum}`);
        if (!questionCard) return;
        
        const radioInputs = questionCard.querySelectorAll('input[type="radio"]');
        const checkboxInputs = questionCard.querySelectorAll('input[type="checkbox"]');
        
        let isAnswered = false;
        
        // Check radio buttons
        radioInputs.forEach(input => {
            if (input.checked) {
                isAnswered = true;
            }
        });
        
        // Check checkboxes
        checkboxInputs.forEach(input => {
            if (input.checked) {
                isAnswered = true;
            }
        });
        
        // Update navigation button class and remove active class
        if (isAnswered) {
            navBtn.classList.add('answered');
            navBtn.classList.remove('active');
        } else {
            navBtn.classList.remove('answered');
        }
    }

    // Initialize navigation state on page load
    function initializeNavigationState() {
        for (let i = 1; i <= totalQuestions; i++) {
            markAsAnswered(i);
        }
    }

    // Start timer when exam is started (only for students/peserta role)
    @hasanyrole('admin|super admin|pengajar')
        // Admin, super admin, and pengajar - timer is static, does not run
        console.log('Timer disabled for admin/pengajar role - showing static time');
        // Don't call startTimer() - keep timer static at 60:00
    @else
        // Timer will be started when startExam() is called
        console.log('Timer will start when exam begins');
    @endhasanyrole
    
    // Function to start exam (for peserta only) - UPDATED untuk layout baru
    function startExam() {
        // Hide start screen
        const startScreen = document.getElementById('examStartScreen');
        if (startScreen) {
            startScreen.style.display = 'none';
        }
        
        // Show form container
        const formContainer = document.getElementById('examFormContainer');
        if (formContainer) {
            formContainer.style.display = 'block';
        }
        
        // Show exam navbar (header dengan progress & timer)
        const examNavbar = document.getElementById('examNavbar');
        if (examNavbar) {
            examNavbar.style.display = 'block';
        }
        
        // Show sidebar (timer and navigation)
        const sidebar = document.getElementById('examSidebar');
        if (sidebar) {
            sidebar.style.display = 'block';
        }
        
        // Add exam-active-mode class to body for layout changes
        document.body.classList.add('exam-active-mode');
        
        // Add exam-active class for grid layout
        const examContainer = document.getElementById('examContainer');
        if (examContainer) {
            examContainer.classList.add('exam-active');
        }
        
        // Start the timer
        startTimer();
        
        // Update progress bar
        updateProgressBar(1);
    }
    
    // Update progress bar and question info
    function updateProgressBar(currentQuestion) {
        const totalQuestions = {{ $ujian->soal->count() }};
        const percentage = (currentQuestion / totalQuestions) * 100;
        
        const progressBar = document.getElementById('progressBar');
        const questionProgress = document.getElementById('questionProgress');
        
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }
        if (questionProgress) {
            questionProgress.textContent = `Pertanyaan ${currentQuestion} dari ${totalQuestions}`;
        }
    }
    
    // Sync navbar timer with sidebar timer
    function syncTimerDisplay(timeString) {
        const timerNavbar = document.getElementById('timerNavbar');
        if (timerNavbar) {
            timerNavbar.textContent = timeString;
        }
    }
    
    // Initialize navigation state
    initializeNavigationState();
    @endif

    // Form submission
    @if($ujian->tipe === 'practice')
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        @hasanyrole('peserta')
        // Langsung submit tanpa konfirmasi
        submitQuiz();
        @else
        showToast('Info', 'Fitur ini hanya untuk peserta', 'info');
        @endhasanyrole
    });
    
    function submitQuiz() {
        const formData = new FormData(document.getElementById('quizForm'));
        const jawaban = {};
        
        // Collect all answers
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('soal_')) {
                const soalId = key.replace('soal_', '').replace('[]', '');
                if (key.includes('[]')) {
                    // Multiple answer
                    if (!jawaban[soalId]) {
                        jawaban[soalId] = [];
                    }
                    jawaban[soalId].push(value);
                } else {
                    // Single answer
                    jawaban[soalId] = value;
                }
            }
        }
        
        // Submit to backend for quiz (practice type)
        fetch('{{ route("user.ujian.submit", $ujian->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ 
                jawaban: jawaban,
                is_practice: true 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // For quiz, reload with results parameter (tanpa alert)
                window.location.href = window.location.href.split('?')[0] + '?show_results=1';
            } else {
                showToast('Gagal', 'Gagal menyelesaikan kuis: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menyelesaikan kuis', 'error');
        });
    }
    @else
    document.getElementById('examForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        @hasanyrole('peserta')
        // Langsung submit tanpa konfirmasi
        submitExam();
        @else
        showToast('Info', 'Fitur ini hanya untuk peserta', 'info');
        @endhasanyrole
    });
    
    function submitExam() {
        const formData = new FormData(document.getElementById('examForm'));
        const jawaban = {};
        
        // Collect all answers
        for (let [key, value] of formData.entries()) {
            if (key.startsWith('soal_')) {
                const soalId = key.replace('soal_', '').replace('[]', '');
                if (key.includes('[]')) {
                    // Multiple answer
                    if (!jawaban[soalId]) {
                        jawaban[soalId] = [];
                    }
                    jawaban[soalId].push(value);
                } else {
                    // Single answer
                    jawaban[soalId] = value;
                }
            }
        }
        
        // Submit to backend (tanpa alert)
        fetch('{{ route("user.ujian.submit", $ujian->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ jawaban: jawaban })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("user.ujian.result", $ujian->id) }}';
            } else {
                showToast('Gagal', 'Gagal menyelesaikan ujian: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menyelesaikan ujian', 'error');
        });
    }
    @endif

    // Modal Soal Functions
    const maxPilihanJawaban = 5;

    function updateAddPilihanButton() {
        const container = document.getElementById('pilihanJawabanContainer');
        const button = document.getElementById('btnAddPilihan');
        if (!container || !button) return;
        const count = container.querySelectorAll('.pilihan-item-soal').length;
        button.style.display = count >= maxPilihanJawaban ? 'none' : 'inline-flex';
    }

    function openAddSoalModal() {
        // Reset form for add mode
        document.getElementById('soalMethod').value = 'POST';
        document.getElementById('soalId').value = '';
        document.querySelector('.modal-title-soal').textContent = 'Tambah Soal';
        document.querySelector('.modal-subtitle-soal').textContent = 'Buat soal baru untuk {{ $ujian->tipe === "practice" ? "kuis" : "ujian" }}: {{ $ujian->judul }}';
        
        document.getElementById('modalSoal').classList.add('active');
        document.body.style.overflow = 'hidden';
        updateAddPilihanButton();
    }

    function closeAddSoalModal() {
        document.getElementById('modalSoal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('formSoal').reset();
        
        // Reset lampiran foto
        removeLampiranFoto();
        
        // Reset pilihan jawaban
        const container = document.getElementById('pilihanJawabanContainer');
        container.innerHTML = `
            <div class="pilihan-item-soal">
                <span class="pilihan-label-soal">A</span>
                <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan A" required>
                <label class="radio-label-soal">
                    <input type="radio" name="kunci_jawaban" value="0" required>
                    <span class="radio-custom-soal"></span>
                </label>
            </div>
            <div class="pilihan-item-soal">
                <span class="pilihan-label-soal">B</span>
                <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan B" required>
                <label class="radio-label-soal">
                    <input type="radio" name="kunci_jawaban" value="1" required>
                    <span class="radio-custom-soal"></span>
                </label>
            </div>
        `;
        updateAddPilihanButton();
    }

    function editSoal(soalId) {
        fetch(`/admin/soal/${soalId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const soal = data.data;
                    
                    // Set form to edit mode
                    document.getElementById('soalMethod').value = 'PUT';
                    document.getElementById('soalId').value = soalId;
                    document.querySelector('.modal-title-soal').textContent = 'Edit Soal';
                    document.querySelector('.modal-subtitle-soal').textContent = 'Edit soal untuk {{ $ujian->tipe === "practice" ? "kuis" : "ujian" }}: {{ $ujian->judul }}';
                    
                    // Fill form
                    document.querySelector('textarea[name="pertanyaan"]').value = soal.pertanyaan;
                    document.querySelector('textarea[name="pembahasan"]').value = soal.pembahasan || '';
                    
                    // Set tipe soal
                    const tipeSoalInputs = document.querySelectorAll('input[name="tipe_soal"]');
                    tipeSoalInputs.forEach(input => {
                        if (input.value === soal.tipe_soal) {
                            input.checked = true;
                        }
                    });
                    
                    // Show existing lampiran foto if exists
                    if (soal.lampiran_foto) {
                        const preview = document.getElementById('previewImage');
                        const container = document.getElementById('previewContainer');
                        const text = document.getElementById('lampiranFotoText');
                        
                        preview.src = '/storage/' + soal.lampiran_foto;
                        container.style.display = 'block';
                        text.textContent = 'Foto saat ini (klik untuk ganti)';
                    }
                    
                    // Fill pilihan jawaban
                    const container = document.getElementById('pilihanJawabanContainer');
                    container.innerHTML = '';
                    const labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                    
                    soal.pilihan.forEach((pilihan, index) => {
                        const div = document.createElement('div');
                        div.className = 'pilihan-item-soal';
                        div.innerHTML = `
                            <span class="pilihan-label-soal">${labels[index]}</span>
                            <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan ${labels[index]}" value="${pilihan}" required>
                            <label class="radio-label-soal">
                                <input type="radio" name="kunci_jawaban" value="${index}" ${index === soal.kunci_jawaban ? 'checked' : ''} required>
                                <span class="radio-custom-soal"></span>
                            </label>
                            ${index >= 2 ? `<button type="button" onclick="removePilihanJawaban(this)" class="btn-delete-pilihan-soal">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>` : ''}
                        `;
                        container.appendChild(div);
                    });
                    updateAddPilihanButton();
                    
                    // Open modal
                    document.getElementById('modalSoal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    showToast('Gagal', 'Gagal mengambil data soal', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error', 'Terjadi kesalahan saat mengambil data soal', 'error');
            });
    }

    let deleteSoalId = null;

    function deleteSoalConfirm(soalId, pertanyaan) {
        deleteSoalId = soalId;
        const modal = document.getElementById('deleteSoalModal');
        const text = document.getElementById('deleteSoalText');
        text.textContent = `Apakah Anda yakin ingin menghapus soal: "${pertanyaan.substring(0, 50)}..."?`;
        modal.classList.remove('closing');
        modal.classList.add('active');
    }

    function closeDeleteSoalModal() {
        const modal = document.getElementById('deleteSoalModal');
        modal.classList.add('closing');
        setTimeout(() => {
            modal.classList.remove('active', 'closing');
            deleteSoalId = null;
        }, 200);
    }

    function confirmDeleteSoal() {
        if (!deleteSoalId) return;
        
        closeDeleteSoalModal();
        
        fetch(`/admin/soal/${deleteSoalId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Berhasil', 'Soal berhasil dihapus', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Gagal', 'Gagal menghapus soal: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Terjadi kesalahan saat menghapus soal', 'error');
        });
    }

    function changeTipeSoal(tipe) {
        const container = document.getElementById('pilihanJawabanContainer');
        const infoText = document.getElementById('infoTextJawaban');
        const items = container.querySelectorAll('.pilihan-item-soal');
        
        if (tipe === 'multiple') {
            // Multi-answer: gunakan checkbox
            infoText.textContent = 'Centang checkbox untuk menandai jawaban yang benar (bisa lebih dari 1)';
            
            items.forEach((item, index) => {
                const selector = item.querySelector('.jawaban-selector');
                const oldInput = selector.querySelector('.jawaban-input');
                const isChecked = oldInput.checked;
                
                selector.innerHTML = `
                    <input type="checkbox" name="kunci_jawaban[]" value="${index}" class="jawaban-input checkbox-custom" ${isChecked ? 'checked' : ''}>
                    <span class="checkbox-custom-soal"></span>
                `;
            });
        } else {
            // Single answer: gunakan radio
            infoText.textContent = 'Pilih radio button untuk menandai jawaban yang benar';
            
            items.forEach((item, index) => {
                const selector = item.querySelector('.jawaban-selector');
                const oldInput = selector.querySelector('.jawaban-input');
                const isChecked = oldInput.checked;
                
                selector.innerHTML = `
                    <input type="radio" name="kunci_jawaban" value="${index}" class="jawaban-input" ${isChecked ? 'checked' : ''} required>
                    <span class="radio-custom-soal"></span>
                `;
            });
        }
    }

    function removePilihanJawaban(button) {
        if (!button) return;
        const container = document.getElementById('pilihanJawabanContainer');
        button.parentElement.remove();
        updateAddPilihanButton();

        if (container) {
            const items = container.querySelectorAll('.pilihan-item-soal');
            items.forEach((item, index) => {
                const input = item.querySelector('.jawaban-input');
                if (input) {
                    input.value = index;
                }
            });
        }
    }

    function addPilihanJawaban() {
        const container = document.getElementById('pilihanJawabanContainer');
        const index = container.children.length;
        if (index >= maxPilihanJawaban) {
            updateAddPilihanButton();
            return;
        }
        const labels = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        const label = labels[index - 2] || String.fromCharCode(67 + index - 2);
        const tipeSoal = document.querySelector('input[name="tipe_soal"]:checked').value;
        
        const div = document.createElement('div');
        div.className = 'pilihan-item-soal';
        
        if (tipeSoal === 'multiple') {
            div.innerHTML = `
                <span class="pilihan-label-soal">${label}</span>
                <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan ${label}" required>
                <label class="radio-label-soal jawaban-selector">
                    <input type="checkbox" name="kunci_jawaban[]" value="${index}" class="jawaban-input checkbox-custom">
                    <span class="checkbox-custom-soal"></span>
                </label>
                <button type="button" onclick="removePilihanJawaban(this)" class="btn-delete-pilihan-soal">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
        } else {
            div.innerHTML = `
                <span class="pilihan-label-soal">${label}</span>
                <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan ${label}" required>
                <label class="radio-label-soal jawaban-selector">
                    <input type="radio" name="kunci_jawaban" value="${index}" class="jawaban-input" required>
                    <span class="radio-custom-soal"></span>
                </label>
                <button type="button" onclick="removePilihanJawaban(this)" class="btn-delete-pilihan-soal">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
        }
        container.appendChild(div);
        updateAddPilihanButton();
    }

    // Form submission for soal
    document.addEventListener('DOMContentLoaded', function() {
        const formSoal = document.getElementById('formSoal');
        if (formSoal) {
            formSoal.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const method = document.getElementById('soalMethod').value;
                const soalId = document.getElementById('soalId').value;
                
                // Tambahkan method untuk PUT request
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }
                
                // Determine URL based on method
                let url = '{{ route("admin.soal.store") }}';
                if (method === 'PUT' && soalId) {
                    url = `/admin/soal/${soalId}`;
                }
                
                // Disable submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10" stroke-width="4" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-width="4"/></svg> Menyimpan...';
                
                fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, assume success and reload
                        throw new Error('Non-JSON response, reloading page...');
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        showToast('Berhasil', 'Soal berhasil disimpan!', 'success');
                        closeAddSoalModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal', 'Gagal menyimpan soal: ' + (data.message || 'Unknown error'), 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.log('Error or non-JSON response, reloading page...');
                    // Auto reload on error/non-JSON response
                    closeAddSoalModal();
                    location.reload();
                });
            });
        }

        // Import form submission
        const formImport = document.getElementById('formImport');
        if (formImport) {
            formImport.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                // Disable submit button
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="animation: spin 1s linear infinite;"><circle cx="12" cy="12" r="10" stroke-width="4" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-width="4"/></svg> Mengimport...';
                
                fetch('{{ route("admin.soal.import") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Non-JSON response, reloading page...');
                    }
                })
                .then(data => {
                    if (data && data.success) {
                        showToast('Berhasil', 'Soal berhasil diimport!', 'success');
                        closeImportModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Gagal', 'Gagal mengimport soal: ' + (data.message || 'Unknown error'), 'error');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.log('Error or non-JSON response, reloading page...');
                    closeImportModal();
                    location.reload();
                });
            });
        }
    });

    // Import/Export functions
    function openImportModal() {
        document.getElementById('modalImport').classList.add('active');
    }

    function closeImportModal() {
        document.getElementById('modalImport').classList.remove('active');
        document.getElementById('formImport').reset();
        document.getElementById('fileName').textContent = 'Pilih file atau drag & drop di sini';
        document.querySelector('.file-upload-label').classList.remove('has-file');
    }

    function updateFileName() {
        const input = document.getElementById('fileInput');
        const label = document.querySelector('.file-upload-label');
        const fileName = document.getElementById('fileName');
        
        if (input.files.length > 0) {
            fileName.textContent = input.files[0].name;
            label.classList.add('has-file');
        } else {
            fileName.textContent = 'Pilih file atau drag & drop di sini';
            label.classList.remove('has-file');
        }
    }

    function downloadTemplate() {
        window.location.href = '{{ route("admin.soal.template") }}';
    }

    function exportSoal() {
        window.location.href = '{{ route("admin.soal.export", $ujian->id) }}';
    }

    // Lampiran Foto Functions
    function previewLampiranFoto() {
        const input = document.getElementById('lampiranFoto');
        const preview = document.getElementById('previewImage');
        const container = document.getElementById('previewContainer');
        const text = document.getElementById('lampiranFotoText');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
                text.textContent = input.files[0].name;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeLampiranFoto() {
        const input = document.getElementById('lampiranFoto');
        const container = document.getElementById('previewContainer');
        const text = document.getElementById('lampiranFotoText');
        
        input.value = '';
        container.style.display = 'none';
        text.textContent = 'Pilih foto atau drag & drop';
    }
</script>

<!-- Modal Tambah Soal -->
<div id="modalSoal" class="modal-overlay">
    <div class="modal-container-soal">
        <div class="modal-header-soal">
            <div>
                <h2 class="modal-title-soal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="display: inline-block; margin-right: 0.5rem;">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" fill="currentColor"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h6a1 1 0 100-2H7zm0 4a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd" fill="currentColor"/>
                    </svg>
                    Tambah Soal
                </h2>
                <p class="modal-subtitle-soal">Buat soal baru untuk {{ $ujian->tipe === 'practice' ? 'kuis' : 'ujian' }}: {{ $ujian->judul }}</p>
            </div>
            <button class="modal-close-soal" type="button" onclick="closeAddSoalModal()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <form id="formSoal">
            @csrf
            <input type="hidden" id="soalMethod" name="_method" value="POST">
            <input type="hidden" id="soalId" name="soal_id" value="">
            <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
            <div class="modal-body-soal">
                <div class="form-group-soal">
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        Pertanyaan <span style="color: #EF4444;">*</span>
                    </label>
                    <textarea name="pertanyaan" class="form-input-soal" rows="4" placeholder="Ketik pertanyaan di sini..." required></textarea>
                </div>
                
                <div class="form-group-soal">
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" fill="currentColor"/>
                        </svg>
                        Lampiran Foto (Opsional)
                    </label>
                    <div class="file-upload-soal">
                        <input type="file" name="lampiran_foto" id="lampiranFoto" accept="image/*" onchange="previewLampiranFoto()" style="display: none;">
                        <label for="lampiranFoto" class="file-upload-label-soal">
                            <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"/>
                            </svg>
                            <span id="lampiranFotoText">Pilih foto atau drag & drop</span>
                        </label>
                        <div id="previewContainer" style="display: none; margin-top: 0.75rem;">
                            <img id="previewImage" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #E5E7EB;">
                            <button type="button" onclick="removeLampiranFoto()" style="display: block; margin-top: 0.5rem; padding: 0.25rem 0.5rem; background: #FEE2E2; color: #DC2626; border: none; border-radius: 6px; cursor: pointer; font-size: 0.875rem;">
                                Hapus Foto
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group-soal">
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm1 8a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                        </svg>
                        Tipe Soal <span style="color: #EF4444;">*</span>
                    </label>
                    <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="tipe_soal" value="single" checked style="width: 18px; height: 18px; cursor: pointer;" onchange="changeTipeSoal(this.value)">
                            <span>Pilihan Tunggal</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                            <input type="radio" name="tipe_soal" value="multiple" style="width: 18px; height: 18px; cursor: pointer;" onchange="changeTipeSoal(this.value)">
                            <span>Pilihan Ganda (Multi-answer)</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group-soal">
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                        </svg>
                        Pilihan Jawaban <span style="color: #EF4444;">*</span>
                    </label>
                    <div class="info-box-soal" id="infoBoxJawaban">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span id="infoTextJawaban">Pilih radio button untuk menandai jawaban yang benar</span>
                    </div>
                    <div id="pilihanJawabanContainer" class="pilihan-container-soal">
                        <div class="pilihan-item-soal">
                            <span class="pilihan-label-soal">A</span>
                            <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan A" required>
                            <label class="radio-label-soal jawaban-selector">
                                <input type="radio" name="kunci_jawaban" value="0" class="jawaban-input" required>
                                <span class="radio-custom-soal"></span>
                            </label>
                        </div>
                        <div class="pilihan-item-soal">
                            <span class="pilihan-label-soal">B</span>
                            <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan B" required>
                            <label class="radio-label-soal jawaban-selector">
                                <input type="radio" name="kunci_jawaban" value="1" class="jawaban-input" required>
                                <span class="radio-custom-soal"></span>
                            </label>
                        </div>
                    </div>
                    <button type="button" onclick="addPilihanJawaban()" class="btn-add-pilihan-soal" id="btnAddPilihan">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah Pilihan Lainnya
                    </button>
                </div>

                <div class="form-group-soal">
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        Pembahasan (Opsional)
                    </label>
                    <textarea name="pembahasan" class="form-input-soal" rows="4" placeholder="Ketik pembahasan jawaban yang benar (opsional)..."></textarea>
                    <p style="font-size: 0.75rem; color: #6B7280; margin-top: 0.5rem;">Pembahasan akan ditampilkan kepada siswa setelah mengerjakan soal</p>
                </div>
            </div>
            <div class="modal-footer-soal">
                <button type="button" class="btn-cancel-soal" onclick="closeAddSoalModal()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" class="btn-submit-soal">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Simpan Soal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Import Soal -->
<div id="modalImport" class="modal-overlay">
    <div class="modal-container-soal" style="max-width: 600px;">
        <div class="modal-header-soal">
            <div>
                <h2 class="modal-title-soal">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.5rem;">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Import Soal dari Excel
                </h2>
                <p class="modal-subtitle-soal">Upload file Excel (.xlsx) untuk mengimport soal</p>
            </div>
            <button class="modal-close-soal" type="button" onclick="closeImportModal()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <form id="formImport" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
            <div class="modal-body-soal">
                <div class="form-group-soal">
                    <div class="info-box-soal" style="margin-bottom: 1rem; background: #EFF6FF; border-color: #3B82F6;">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="#3B82F6">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong>Format File Excel:</strong>
                            <ul style="margin: 0.5rem 0 0 1.5rem; font-size: 0.875rem;">
                                <li>Kolom: Pertanyaan | Pilihan A | Pilihan B | Pilihan C | Pilihan D | Kunci Jawaban (A/B/C/D) | Kategori</li>
                                <li>Download template untuk melihat contoh format</li>
                                <li>File maksimal 2MB</li>
                            </ul>
                        </div>
                    </div>
                    
                    <label class="form-label-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.25rem;">
                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                        </svg>
                        File Excel <span style="color: #EF4444;">*</span>
                    </label>
                    <div class="file-upload-container">
                        <input type="file" id="fileInput" name="file" accept=".xlsx,.xls" required style="display: none;" onchange="updateFileName()">
                        <label for="fileInput" class="file-upload-label">
                            <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                            <span id="fileName">Pilih file atau drag & drop di sini</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer-soal">
                <button type="button" class="btn-cancel-soal" onclick="closeImportModal()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Batal
                </button>
                <button type="submit" class="btn-submit-soal">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Import Soal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Bank Soal -->
<div id="modalBankSoal" class="modal-overlay">
    <div class="modal-container-soal" style="max-width: 900px; max-height: 85vh;">
        <div class="modal-header-soal" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
            <div>
                <h2 class="modal-title-soal">
                    <svg width="24" height="24" viewBox="0 0 20 20" fill="currentColor" style="display: inline-block; margin-right: 0.5rem;">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    Tambah Soal dari Bank Soal
                </h2>
                <p class="modal-subtitle-soal">Pilih soal yang ingin ditambahkan ke {{ $ujian->tipe === 'practice' ? 'kuis' : 'ujian' }} ini</p>
            </div>
            <button class="modal-close-soal" type="button" onclick="closeBankSoalModal()">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
        <div class="modal-body-soal" style="padding: 1.5rem 2rem; max-height: 55vh; overflow-y: auto;">
            <!-- Filter & Search -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <input type="text" id="bankSoalSearch" placeholder="Cari pertanyaan..." 
                           style="width: 100%; padding: 0.625rem 1rem; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 0.875rem;"
                           onkeyup="filterBankSoal()">
                </div>
                <div>
                    <select id="bankSoalTipe" style="padding: 0.625rem 1rem; border: 1px solid #E2E8F0; border-radius: 8px; font-size: 0.875rem; min-width: 150px;" onchange="filterBankSoal()">
                        <option value="">Semua Tipe</option>
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="multi_jawaban">Multi Jawaban</option>
                    </select>
                </div>
            </div>
            
            <!-- Info kategori -->
            <div style="background: #F5F3FF; border: 1px solid #DDD6FE; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="#7C3AED">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span style="font-size: 0.875rem; color: #5B21B6;">
                    Menampilkan soal dari kategori: <strong>{{ $ujian->modul->kursus->kategori ?? 'Semua' }}</strong>
                </span>
            </div>
            
            <!-- Soal List -->
            <div id="bankSoalList" style="display: flex; flex-direction: column; gap: 0.75rem;">
                <div style="text-align: center; padding: 2rem; color: #64748B;">
                    <svg width="48" height="48" viewBox="0 0 20 20" fill="#CBD5E1" style="margin: 0 auto 1rem;">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                    </svg>
                    Memuat soal...
                </div>
            </div>
        </div>
        <div class="modal-footer-soal" style="border-top: 1px solid #E2E8F0; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center;">
            <div style="font-size: 0.875rem; color: #64748B;">
                <span id="selectedCount">0</span> soal dipilih
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" class="btn-cancel-soal" onclick="closeBankSoalModal()">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Batal
                </button>
                <button type="button" class="btn-submit-soal" onclick="addSelectedSoal()" style="background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Tambahkan Soal
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal Soal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(2px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.modal-container-soal {
    background: white;
    border-radius: 16px;
    max-width: 700px;
    width: 90%;
    max-height: 90vh;
    overflow: hidden;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header-soal {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modal-title-soal {
    font-size: 1.375rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
}

.modal-subtitle-soal {
    font-size: 0.875rem;
    opacity: 0.9;
    margin: 0.5rem 0 0 0;
}

.modal-close-soal {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    color: white;
    transition: all 0.2s;
}

.modal-close-soal:hover {
    background: rgba(255, 255, 255, 0.3);
}

.modal-body-soal {
    padding: 2rem;
    max-height: calc(90vh - 250px);
    overflow-y: auto;
}

.modal-body-soal::-webkit-scrollbar {
    width: 8px;
}

.modal-body-soal::-webkit-scrollbar-track {
    background: #F3F4F6;
}

.modal-body-soal::-webkit-scrollbar-thumb {
    background: #D1D5DB;
    border-radius: 4px;
}

.modal-footer-soal {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    padding: 1.5rem 2rem;
    background: #F9FAFB;
    border-top: 1px solid #E5E7EB;
}

.form-group-soal {
    margin-bottom: 2rem;
}

.form-label-soal {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.75rem;
}

.form-input-soal {
    width: 100%;
    padding: 0.875rem;
    border: 2px solid #E5E7EB;
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: inherit;
    transition: all 0.2s;
    resize: vertical;
}

.form-input-soal:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.info-box-soal {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: #EFF6FF;
    border: 1px solid #DBEAFE;
    border-radius: 8px;
    font-size: 0.813rem;
    color: #1E40AF;
    margin-bottom: 1rem;
}

.pilihan-container-soal {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.pilihan-item-soal {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #F9FAFB;
    border: 2px solid #E5E7EB;
    border-radius: 10px;
    transition: all 0.2s;
}

.pilihan-item-soal:hover {
    border-color: #D1D5DB;
    background: white;
}

.pilihan-label-soal {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.pilihan-input-soal {
    flex: 1;
    padding: 0.625rem;
    border: 1px solid #E5E7EB;
    border-radius: 6px;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.pilihan-input-soal:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.radio-label-soal {
    position: relative;
    cursor: pointer;
    flex-shrink: 0;
}

.radio-label-soal input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.radio-custom-soal {
    display: block;
    width: 24px;
    height: 24px;
    border: 2px solid #D1D5DB;
    border-radius: 50%;
    transition: all 0.2s;
    position: relative;
}

.radio-label-soal input[type="radio"]:checked + .radio-custom-soal {
    border-color: #10B981;
    background: #10B981;
}

    .radio-label-soal input[type="radio"]:checked + .radio-custom-soal::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
    }

    /* Checkbox Custom Styling */
    .checkbox-custom-soal {
        display: block;
        width: 24px;
        height: 24px;
        border: 2px solid #D1D5DB;
        border-radius: 4px;
        transition: all 0.2s;
        position: relative;
    }

    .radio-label-soal input[type="checkbox"] {
        position: absolute;
        opacity: 0;
    }

    .radio-label-soal input[type="checkbox"]:checked + .checkbox-custom-soal {
        border-color: #10B981;
        background: #10B981;
    }

    .radio-label-soal input[type="checkbox"]:checked + .checkbox-custom-soal::after {
        content: '';
        position: absolute;
        top: 2px;
        left: 7px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }.btn-add-pilihan-soal {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: white;
    border: 2px dashed #D1D5DB;
    border-radius: 8px;
    color: #6B7280;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
    margin-top: 0.75rem;
}

.btn-add-pilihan-soal:hover {
    border-color: #667eea;
    color: #667eea;
    background: #F5F3FF;
}

.btn-cancel-soal {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: white;
    border: 1px solid #D1D5DB;
    border-radius: 8px;
    color: #374151;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-cancel-soal:hover {
    background: #F9FAFB;
    border-color: #9CA3AF;
}

.btn-submit-soal {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-submit-soal:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-delete-pilihan-soal {
    background: #FEE2E2;
    border: none;
    padding: 0.5rem;
    border-radius: 6px;
    color: #DC2626;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.btn-delete-pilihan-soal:hover {
    background: #FEcaca;
}

/* Edit/Delete Soal Buttons */
.btn-edit-soal,
.btn-delete-soal {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 0.875rem;
}

.btn-edit-soal {
    background: #EFF6FF;
    color: #3B82F6;
}

.btn-edit-soal:hover {
    background: #DBEAFE;
}

.btn-delete-soal {
    background: #FEF2F2;
    color: #EF4444;
}

.btn-delete-soal:hover {
    background: #FEE2E2;
}

/* Checkbox styling for multiple answer questions */
.option-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    flex-shrink: 0;
}

/* File Upload Styles for Lampiran Foto */
.file-upload-soal {
    margin-top: 0.5rem;
}

.file-upload-label-soal {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    border: 2px dashed #D1D5DB;
    border-radius: 8px;
    background: #F9FAFB;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    gap: 0.5rem;
}

.file-upload-label-soal:hover {
    border-color: #667eea;
    background: #F5F3FF;
}

.file-upload-label-soal svg {
    color: #667eea;
}

.file-upload-label-soal span {
    color: #6B7280;
    font-size: 0.875rem;
}

/* File Upload Styles */
.file-upload-container {
    margin-top: 0.5rem;
}

.file-upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    border: 2px dashed #D1D5DB;
    border-radius: 12px;
    background: #F9FAFB;
    cursor: pointer;
    transition: all 0.3s;
    text-align: center;
    gap: 0.75rem;
}

.file-upload-label:hover {
    border-color: #667eea;
    background: #F5F3FF;
}

.file-upload-label svg {
    color: #667eea;
}

.file-upload-label span {
    color: #6B7280;
    font-size: 0.875rem;
}

.file-upload-label.has-file {
    border-color: #10B981;
    background: #ECFDF5;
}

.file-upload-label.has-file span {
    color: #10B981;
    font-weight: 500;
}
</style>

@role('pengajar')
</div>
@endrole

@endsection
