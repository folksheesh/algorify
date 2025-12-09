<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian - {{ $ujian->judul }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #5D3FFF 0%, #5D3FFF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            width: 100%;
        }

        .result-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .header-icon svg {
            width: 50px;
            height: 50px;
            fill: #10b981;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .score-section {
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .score-circle {
            width: 180px;
            height: 180px;
            margin: 0 auto 30px;
            position: relative;
        }

        .score-circle svg {
            transform: rotate(-90deg);
        }

        .score-circle circle {
            fill: none;
            stroke-width: 12;
        }

        .score-circle .bg-circle {
            stroke: #e5e7eb;
        }

        .score-circle .progress-circle {
            stroke: {{ $nilai->nilai >= 70 ? '#10b981' : '#ef4444' }};
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-out;
        }

        .score-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .score-value {
            font-size: 48px;
            font-weight: 700;
            color: #111827;
            line-height: 1;
        }

        .score-label {
            font-size: 14px;
            color: #6b7280;
            margin-top: 5px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 30px;
            background: #f9fafb;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .stat-item:nth-child(2) .stat-value {
            color: #10b981;
        }

        .stat-item:nth-child(3) .stat-value {
            color: #ef4444;
        }

        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .feedback-section {
            padding: 30px;
            background: linear-gradient(to bottom, #ecfdf5, #d1fae5);
            border-top: 3px solid #10b981;
        }

        .feedback-icon {
            width: 40px;
            height: 40px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .feedback-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .feedback-title {
            font-size: 18px;
            font-weight: 700;
            color: #047857;
            margin-bottom: 10px;
        }

        .feedback-text {
            font-size: 14px;
            color: #065f46;
            line-height: 1.6;
        }

        .action-button {
            display: block;
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #5D3FFF 0%, #5D3FFF 100%);
            color: white;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 15px;
                padding: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .score-circle {
                width: 150px;
                height: 150px;
            }

            .score-value {
                font-size: 36px;
            }

            .stat-value {
                font-size: 24px;
            }
        }
    </style>
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
                    <svg width="180" height="180">
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
            <a href="{{ route('admin.pelatihan.show', $ujian->modul->kursus_id) }}?open_modul={{ $ujian->modul_id }}" class="action-button">
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
