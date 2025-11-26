@extends('layouts.template')

@section('title', $ujian->judul)

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('resources/css/admin/ujian-detail.css') }}">
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

<div class="page-container">
    
    <!-- Back Button -->
    <a href="{{ route('admin.pelatihan.show', $ujian->modul->kursus_id) }}" class="back-btn">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Kembali ke Modul
    </a>

    @if($ujian->tipe === 'practice')
        <!-- Quiz Layout (Simple) -->
        <div class="quiz-container">
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
                        @hasanyrole('admin|pengajar')
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

                    <form id="quizForm">
                        @foreach($ujian->soal as $index => $soal)
                        <div class="question-card">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                                @hasanyrole('admin|pengajar')
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
                                <a href="{{ route('admin.ujian.show', $ujian->id) }}" class="btn btn-primary" style="text-decoration: none;">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                    </svg>
                                    Coba Lagi
                                </a>
                            @else
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                                <button type="submit" class="btn btn-success">
                                    <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Selesai
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Exam Layout (Dengan Navigasi & Timer) -->
        <div class="exam-container">
            <!-- Main Content -->
            <div class="main-content">
                <!-- Exam Header -->
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
                        @hasanyrole('admin|pengajar')
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
                        @endhasanyrole
                    </div>
                </div>

                <!-- Exam Body -->
                <div class="quiz-body">
                    @if($ujian->deskripsi)
                    <p class="description-text">{{ $ujian->deskripsi }}</p>
                    @endif

                    <form id="examForm">
                        @foreach($ujian->soal as $index => $soal)
                        <div class="question-card" id="question-{{ $index + 1 }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                                @hasanyrole('admin|pengajar')
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

                        <div class="action-buttons" id="navigation-buttons">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="navigateQuestion(-1)" style="display: none;">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="navigateQuestion(1)">
                                Selanjutnya
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Selesai Ujian
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Timer -->
                <div class="timer-card">
                    <div class="timer-label">Sisa Waktu</div>
                    <div class="timer-display" id="timer">60:00</div>
                </div>

                <!-- Navigation -->
                <div class="navigation-card">
                    <h3 class="nav-title">Navigasi Soal</h3>
                    
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

<script>
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
            } else if (checkbox) {
                // Checkbox logic (multiple choice)
                checkbox.checked = !checkbox.checked;
                if (checkbox.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        });
    });

    // Function to open Bank Soal modal
    function openBankSoalModal() {
        alert('Fitur Bank Soal akan segera hadir!');
        // TODO: Implement bank soal modal
    }

    @if($ujian->tipe === 'exam')
    // Exam specific JavaScript
    let currentQuestion = 1;
    const totalQuestions = {{ $ujian->soal->count() }};
    let timeLeft = 60 * 60; // 60 minutes in seconds

    // Timer
    function startTimer() {
        const timerDisplay = document.getElementById('timer');
        
        setInterval(() => {
            if (timeLeft <= 0) {
                document.getElementById('examForm').submit();
                return;
            }
            
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            // Change color based on time left
            if (timeLeft <= 300) { // 5 minutes
                timerDisplay.classList.add('danger');
                timerDisplay.classList.remove('warning');
            } else if (timeLeft <= 600) { // 10 minutes
                timerDisplay.classList.add('warning');
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
        
        // Check if question is answered by checking both radio and checkbox inputs
        const questionCard = document.getElementById(`question-${questionNum}`);
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

    // Start timer when page loads (only for students/peserta role)
    @hasanyrole('admin|super admin|pengajar')
        // Admin, super admin, and pengajar don't have countdown timer
        console.log('Timer disabled for admin/pengajar role');
    @else
        startTimer();
    @endhasanyrole
    
    // Initialize navigation state
    initializeNavigationState();
    @endif

    // Form submission
    @if($ujian->tipe === 'practice')
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        @hasanyrole('peserta')
        if (confirm('Apakah Anda yakin ingin menyelesaikan kuis ini?')) {
            const formData = new FormData(this);
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
                    // For quiz, reload with results parameter
                    window.location.href = window.location.href + '?show_results=1';
                } else {
                    alert('Gagal menyelesaikan kuis: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyelesaikan kuis');
            });
        }
        @else
        alert('Fitur ini hanya untuk peserta');
        @endhasanyrole
    });
    @else
    document.getElementById('examForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        @hasanyrole('peserta')
        if (confirm('Apakah Anda yakin ingin menyelesaikan ujian ini?')) {
            const formData = new FormData(this);
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
            
            // Submit to backend
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
                    alert('Gagal menyelesaikan ujian: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyelesaikan ujian');
            });
        }
        @else
        alert('Fitur ini hanya untuk peserta');
        @endhasanyrole
    });
    @endif

    // Modal Soal Functions
    function openAddSoalModal() {
        // Reset form for add mode
        document.getElementById('soalMethod').value = 'POST';
        document.getElementById('soalId').value = '';
        document.querySelector('.modal-title-soal').textContent = 'Tambah Soal';
        document.querySelector('.modal-subtitle-soal').textContent = 'Buat soal baru untuk {{ $ujian->tipe === "practice" ? "kuis" : "ujian" }}: {{ $ujian->judul }}';
        
        document.getElementById('modalSoal').classList.add('active');
        document.body.style.overflow = 'hidden';
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
                            ${index >= 2 ? `<button type="button" onclick="this.parentElement.remove()" class="btn-delete-pilihan-soal">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>` : ''}
                        `;
                        container.appendChild(div);
                    });
                    
                    // Open modal
                    document.getElementById('modalSoal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    alert('Gagal mengambil data soal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data soal');
            });
    }

    function deleteSoalConfirm(soalId, pertanyaan) {
        if (confirm(`Apakah Anda yakin ingin menghapus soal: "${pertanyaan.substring(0, 50)}..."?`)) {
            fetch(`/admin/soal/${soalId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menghapus soal: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus soal');
            });
        }
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

    function addPilihanJawaban() {
        const container = document.getElementById('pilihanJawabanContainer');
        const index = container.children.length;
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
                <button type="button" onclick="this.parentElement.remove()" class="btn-delete-pilihan-soal">
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
                <button type="button" onclick="this.parentElement.remove()" class="btn-delete-pilihan-soal">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
        }
        container.appendChild(div);
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
                        alert('Soal berhasil disimpan!');
                        closeAddSoalModal();
                        location.reload();
                    } else {
                        alert('Gagal menyimpan soal: ' + (data.message || 'Unknown error'));
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
                        alert('Soal berhasil diimport!');
                        closeImportModal();
                        location.reload();
                    } else {
                        alert('Gagal mengimport soal: ' + (data.message || 'Unknown error'));
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
                    <button type="button" onclick="addPilihanJawaban()" class="btn-add-pilihan-soal">
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

@endsection

