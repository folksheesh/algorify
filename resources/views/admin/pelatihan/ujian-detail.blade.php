@extends('layouts.template')

@section('title', $ujian->judul)

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
    
    /* Layout untuk Quiz (Simple) */
    .quiz-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    /* Layout untuk Ujian (Dengan Navigasi) */
    .exam-container {
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
    
    .quiz-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        color: white;
    }
    
    .quiz-meta {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .quiz-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quiz-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }
    
    .quiz-body {
        padding: 2rem;
    }
    
    .question-card {
        background: white;
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .question-number {
        font-size: 0.875rem;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 0.75rem;
    }
    
    .question-text {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }
    
    .options-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .option-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #F8FAFC;
        border: 2px solid #E2E8F0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .option-item:hover {
        border-color: #667eea;
        background: #EEF2FF;
    }
    
    .option-item.selected {
        border-color: #667eea;
        background: #EEF2FF;
    }
    
    .option-radio {
        width: 20px;
        height: 20px;
        margin-right: 1rem;
        cursor: pointer;
    }
    
    .option-text {
        flex: 1;
        color: #1F2937;
        font-size: 0.9375rem;
    }
    
    /* Timer untuk Ujian */
    .timer-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .timer-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.5rem;
    }
    
    .timer-display {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
    }
    
    .timer-display.warning {
        color: #F59E0B;
    }
    
    .timer-display.danger {
        color: #EF4444;
    }
    
    /* Navigasi Soal untuk Ujian */
    .navigation-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }
    
    .nav-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1F2937;
        margin-bottom: 1rem;
    }
    
    .question-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .question-nav-btn {
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
    
    .question-nav-btn:hover {
        border-color: #667eea;
    }
    
    .question-nav-btn.active {
        background: #667eea;
        border-color: #667eea;
        color: white;
    }
    
    .question-nav-btn.answered {
        background: #10B981;
        border-color: #10B981;
        color: white;
    }
    
    .legend {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        padding-top: 1rem;
        border-top: 1px solid #E2E8F0;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
        color: #6B7280;
    }
    
    .legend-box {
        width: 20px;
        height: 20px;
        border-radius: 4px;
    }
    
    .legend-box.current {
        background: #667eea;
    }
    
    .legend-box.answered {
        background: #10B981;
    }
    
    .legend-box.unanswered {
        background: #F1F5F9;
        border: 2px solid #E2E8F0;
    }
    
    /* Tombol Aksi */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: space-between;
        margin-top: 2rem;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-secondary {
        background: white;
        color: #64748B;
        border: 2px solid #E2E8F0;
    }
    
    .btn-secondary:hover {
        background: #F8FAFC;
    }
    
    .btn-primary {
        background: #667eea;
        color: white;
    }
    
    .btn-primary:hover {
        background: #5568d3;
    }
    
    .btn-success {
        background: #10B981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .description-text {
        color: #6B7280;
        line-height: 1.6;
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #E2E8F0;
    }
    
    @media (max-width: 1024px) {
        .exam-container {
            grid-template-columns: 1fr;
        }
        
        .navigation-card {
            order: -1;
        }
    }
</style>
@endpush

@section('content')
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
                        <button onclick="openAddSoalModal()" class="btn btn-primary" style="margin-left: auto;">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.5rem;">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Tambah Soal
                        </button>
                        @endhasanyrole
                    </div>
                </div>

                <!-- Quiz Body -->
                <div class="quiz-body">
                    @if($ujian->deskripsi)
                    <p class="description-text">{{ $ujian->deskripsi }}</p>
                    @endif

                    <form id="quizForm">
                        @foreach($ujian->soal as $index => $soal)
                        <div class="question-card">
                            <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                            <div class="question-text">{{ $soal->pertanyaan }}</div>
                            
                            <div class="options-list">
                                @foreach($soal->pilihanJawaban as $pilihan)
                                <label class="option-item">
                                    <input type="radio" name="soal_{{ $soal->id }}" value="{{ $pilihan->id }}" class="option-radio">
                                    <span class="option-text">{{ $pilihan->pilihan }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach

                        <div class="action-buttons">
                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Batal</button>
                            <button type="submit" class="btn btn-success">
                                <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Selesai
                            </button>
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
                        <button onclick="openAddSoalModal()" class="btn btn-primary" style="margin-left: auto;">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="currentColor" style="margin-right: 0.5rem;">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Tambah Soal
                        </button>
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
                            <div class="question-number">Pertanyaan {{ $index + 1 }} dari {{ $ujian->soal->count() }}</div>
                            <div class="question-text">{{ $soal->pertanyaan }}</div>
                            
                            <div class="options-list">
                                @foreach($soal->pilihanJawaban as $pilihan)
                                <label class="option-item" data-question="{{ $index + 1 }}">
                                    <input type="radio" name="soal_{{ $soal->id }}" value="{{ $pilihan->id }}" class="option-radio" onchange="markAsAnswered({{ $index + 1 }})">
                                    <span class="option-text">{{ $pilihan->pilihan }}</span>
                                </label>
                                @endforeach
                            </div>
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
            const questionNum = this.dataset.question;
            
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
        });
    });

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
            document.getElementById(`nav-${i}`).classList.remove('active');
        }
        
        // Show selected question
        document.getElementById(`question-${num}`).style.display = 'block';
        document.getElementById(`nav-${num}`).classList.add('active');
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
        if (!navBtn.classList.contains('active')) {
            navBtn.classList.add('answered');
        }
    }

    // Start timer when page loads
    startTimer();
    @endif

    // Form submission
    @if($ujian->tipe === 'practice')
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Quiz selesai! (Implementasi penilaian akan ditambahkan)');
    });
    @else
    document.getElementById('examForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Apakah Anda yakin ingin menyelesaikan ujian ini?')) {
            alert('Ujian selesai! (Implementasi penilaian akan ditambahkan)');
        }
    });
    @endif

    // Modal Soal Functions
    function openAddSoalModal() {
        document.getElementById('modalSoal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeAddSoalModal() {
        document.getElementById('modalSoal').classList.remove('active');
        document.body.style.overflow = '';
        document.getElementById('formSoal').reset();
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

    function addPilihanJawaban() {
        const container = document.getElementById('pilihanJawabanContainer');
        const index = container.children.length;
        const labels = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        const label = labels[index - 2] || String.fromCharCode(67 + index - 2);
        
        const div = document.createElement('div');
        div.className = 'pilihan-item-soal';
        div.innerHTML = `
            <span class="pilihan-label-soal">${label}</span>
            <input type="text" name="pilihan[]" class="pilihan-input-soal" placeholder="Masukkan pilihan ${label}" required>
            <label class="radio-label-soal">
                <input type="radio" name="kunci_jawaban" value="${index}" required>
                <span class="radio-custom-soal"></span>
            </label>
            <button type="button" onclick="this.parentElement.remove()" class="btn-delete-pilihan-soal">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </button>
        `;
        container.appendChild(div);
    }

    // Form submission for soal
    document.addEventListener('DOMContentLoaded', function() {
        const formSoal = document.getElementById('formSoal');
        if (formSoal) {
            formSoal.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch('{{ route("admin.soal.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeAddSoalModal();
                        location.reload();
                    } else {
                        alert('Gagal menambahkan soal: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menambahkan soal');
                });
            });
        }
    });
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
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                        </svg>
                        Pilihan Jawaban <span style="color: #EF4444;">*</span>
                    </label>
                    <div class="info-box-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span>Pilih radio button untuk menandai jawaban yang benar</span>
                    </div>
                    <div id="pilihanJawabanContainer" class="pilihan-container-soal">
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
                    </div>
                    <button type="button" onclick="addPilihanJawaban()" class="btn-add-pilihan-soal">
                        <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Tambah Pilihan Lainnya
                    </button>
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
    max-height: calc(90vh - 200px);
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

.btn-add-pilihan-soal {
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
</style>

@endsection

