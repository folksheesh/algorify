<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $ujian->judul }}</title>
    <link rel="stylesheet" href="{{ asset('css/peserta/ujian-result.css') }}">
</head>
<body>
    <div class="container">
        <div class="result-card">
            <!-- Header -->
            <div class="header" style="background: linear-gradient(135deg, {{ $nilai->nilai >= 70 ? '#10b981' : '#ef4444' }} 0%, {{ $nilai->nilai >= 70 ? '#059669' : '#dc2626' }} 100%);">
                <div class="header-icon">
                    @if($nilai->nilai >= 70)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                    </svg>
                    @endif
                </div>
                <h1>{{ $nilai->nilai >= 70 ? 'Selamat Anda Lulus!' : 'Anda Belum Lulus' }} {{ $nilai->nilai >= 70 ? '🎉' : '😔' }}</h1>
                <p>Anda telah menyelesaikan ujian {{ $nilai->nilai >= 70 ? 'dengan baik' : 'ini' }}</p>
            </div>

            <!-- Score Circle -->
            <div class="score-section">
                <div class="score-circle">
                    <svg viewBox="0 0 180 180" width="100%" height="100%">
                        <circle class="bg-circle" cx="90" cy="90" r="80"></circle>
                        <circle class="progress-circle" cx="90" cy="90" r="80" 
                                stroke-dasharray="{{ 2 * 3.14159 * 80 }}" 
                                stroke-dashoffset="{{ 2 * 3.14159 * 80 * (1 - $nilai->nilai / 100) }}">
                        </circle>
                    </svg>
                    <div class="score-text">
                        <div class="score-value">{{ number_format($nilai->nilai, 0) }}</div>
                        <div class="score-label">Skor</div>
                    </div>
                </div>
            </div>

            <!-- Statistics Grid -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $totalSoal }}</div>
                    <div class="stat-label">Total Soal</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        <svg style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; fill: #10b981;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                        {{ $benarCount }}
                    </div>
                    <div class="stat-label">Jawaban Benar</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        <svg style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; fill: #ef4444;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                        {{ $salahCount }}
                    </div>
                    <div class="stat-label">Jawaban Salah</div>
                </div>
            </div>

            <!-- Feedback Section -->
            <div class="feedback-section" style="background: linear-gradient(to bottom, {{ $nilai->nilai >= 70 ? '#ecfdf5, #d1fae5' : '#fef2f2, #fee2e2' }}); border-top: 3px solid {{ $nilai->nilai >= 70 ? '#10b981' : '#ef4444' }};">
                <div class="feedback-icon" style="background: {{ $nilai->nilai >= 70 ? '#10b981' : '#ef4444' }};">
                    @if($nilai->nilai >= 90)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    @elseif($nilai->nilai >= 70)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
                    </svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                    </svg>
                    @endif
                </div>
                <div class="feedback-title" style="color: {{ $nilai->nilai >= 70 ? '#047857' : '#991b1b' }};">
                    @if($nilai->nilai >= 90)
                        Performa Luar Biasa!
                    @elseif($nilai->nilai >= 70)
                        Performa Sangat Baik!
                    @else
                        Terus Berlatih!
                    @endif
                </div>
                <div class="feedback-text" style="color: {{ $nilai->nilai >= 70 ? '#065f46' : '#7f1d1d' }};">
                    @if($nilai->nilai >= 90)
                        Sempurna! Anda berhasil menjawab {{ $benarCount }} dari {{ $totalSoal }} pertanyaan dengan benar. Pertahankan performa luar biasa Anda!
                    @elseif($nilai->nilai >= 70)
                        Bagus! Anda berhasil menjawab {{ $benarCount }} dari {{ $totalSoal }} pertanyaan dengan benar. Pertahankan performa Anda!
                    @else
                        Jangan menyerah! Anda menjawab {{ $benarCount }} dari {{ $totalSoal }} pertanyaan dengan benar. Terus berlatih untuk meningkatkan pemahaman Anda.
                    @endif
                </div>
            </div>

            <!-- Action Button -->
            <a href="{{ route('admin.pelatihan.show', $ujian->modul->kursus->slug) }}?open_modul={{ $ujian->modul->slug }}" class="action-button">
                Kembali ke Pelatihan
            </a>
        </div>
    </div>

    <script>
        // Animate score circle on load
        window.addEventListener('load', function() {
            const progressCircle = document.querySelector('.progress-circle');
            const scoreValue = {{ $nilai->nilai }};
            const radius = 80;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference * (1 - scoreValue / 100);
            
            progressCircle.style.strokeDashoffset = offset;
        });
    </script>
</body>
</html>
