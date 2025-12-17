<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Video;

class PelatihanQuarterSeeder extends Seeder
{
    /**
     * Seed pelatihan untuk 1/4 kursus yang ada.
     * Setiap kursus mendapat 6-10 modul (di sini 8) dengan materi dan video ringkas
     * yang mendekati topik kursus asli.
     */
    public function run(): void
    {
        $totalKursus = Kursus::count();
        if ($totalKursus === 0) {
            $this->command->warn('Tidak ada kursus. Jalankan KursusSeeder terlebih dahulu.');
            return;
        }

        $targetCount = max(1, (int) ceil($totalKursus / 4));
        $courses = Kursus::orderBy('id')->take($targetCount)->get();
        $courseIds = $courses->pluck('id');

        // Bersihkan data modul/materi/video untuk kursus terpilih agar tidak duplikat
        DB::transaction(function () use ($courseIds) {
            $modulIds = Modul::whereIn('kursus_id', $courseIds)->pluck('id');
            Video::whereIn('modul_id', $modulIds)->delete();
            Materi::whereIn('modul_id', $modulIds)->delete();
            Modul::whereIn('id', $modulIds)->delete();
        });

        // Template modul per kursus (8 modul, dianggap 8 minggu)
        $templates = $this->moduleTemplates();

        foreach ($courses as $kursus) {
            $template = $templates[$kursus->judul] ?? $this->genericTemplate($kursus->judul);

            DB::transaction(function () use ($kursus, $template) {
                foreach ($template as $index => $mod) {
                    $modul = Modul::create([
                        'kursus_id' => $kursus->id,
                        'judul' => $mod['judul'],
                        'deskripsi' => $mod['deskripsi'],
                        'urutan' => $index + 1,
                    ]);

                    // Materi per modul
                    $urutanMateri = 1;
                    foreach ($mod['materi'] as $materi) {
                        Materi::create([
                            'modul_id' => $modul->id,
                            'judul' => $materi['judul'],
                            'deskripsi' => $materi['deskripsi'],
                            'konten' => $materi['konten'],
                            'urutan' => $urutanMateri++,
                        ]);
                    }

                    // Video placeholder
                    if (!empty($mod['video'])) {
                        Video::create([
                            'modul_id' => $modul->id,
                            'judul' => $mod['video']['judul'],
                            'deskripsi' => $mod['video']['deskripsi'],
                            'file_video' => $mod['video']['file_video'],
                            'urutan' => 1,
                        ]);
                    }
                }
            });
        }

        $this->command->info('✓ PelatihanQuarterSeeder: modul/materi/video dibuat untuk ' . $courses->count() . ' kursus.');
    }

    /**
     * Template untuk kursus terpilih (8 modul).
     */
    private function moduleTemplates(): array
    {
        return [
            'Analisis Data' => $this->buildTemplate([
                ['Pondasi Statistik & Python', 'Dasar statistik deskriptif, Python dasar, dan setup lingkungan data'],
                ['Pandas & Data Wrangling', 'Membersihkan, transformasi, dan menggabungkan dataset dengan pandas'],
                ['Visualisasi Data', 'Plotting dengan Matplotlib/Seaborn, memilih chart yang tepat'],
                ['Analisis Eksploratori (EDA)', 'Mencari pola, outlier, dan insight awal'],
                ['Feature Engineering', 'Membuat fitur baru, encoding, scaling untuk model ML ringan'],
                ['Modeling Dasar', 'Regresi & klasifikasi baseline (Linear/Logistic Regression, tree)'],
                ['Evaluasi & Interpretasi', 'Metrik akurasi, precision/recall, ROC, dan interpretasi'],
                ['Presentasi Insight', 'Menyusun laporan, dashboard ringkas, dan storytelling data'],
            ]),

            'Analisis Keamanan Siber' => $this->buildTemplate([
                ['Dasar Keamanan & Threat Landscape', 'CIA triad, threat model, dan jenis serangan umum'],
                ['Jaringan & Protocol Security', 'TCP/IP, HTTPS, firewall, IDS/IPS dasar'],
                ['Vulnerability Assessment', 'Scanning, enumerasi, dan baseline hardening'],
                ['Web Security', 'OWASP Top 10 ringkas: XSS, SQLi, CSRF, auth issues'],
                ['System & Endpoint Security', 'OS hardening, patching, antivirus/EDR'],
                ['Cryptography Praktis', 'Hash, symmetric/asymmetric, TLS dasar'],
                ['Incident Response', 'Playbook IR: detect, contain, eradicate, recover'],
                ['Monitoring & Reporting', 'Log management, alerting, dan laporan risiko'],
            ]),

            'Desainer UI/UX' => $this->buildTemplate([
                ['Fundamental UX', 'Prinsip usability, heuristic, dan user research ringan'],
                ['User Journey & Persona', 'Menyusun persona, journey map, dan kebutuhan'],
                ['Arsitektur Informasi', 'Sitemap, navigasi, dan struktur konten'],
                ['Wireframe ke Low-Fi', 'Sketsa layar, layout grid, dan hierarchy'],
                ['UI Visual & Design System', 'Tipografi, warna, komponen, dan konsistensi'],
                ['Prototyping Interaktif', 'Membuat prototipe di Figma/Adobe XD dan alur interaksi'],
                ['Usability Testing', 'Rencana tes, task list, dan perbaikan iteratif'],
                ['Handoff ke Dev', 'Spesifikasi, asset, dan dokumentasi komponen'],
            ]),

            'IT Support' => $this->buildTemplate([
                ['Peran IT Support', 'SOP, eskalasi, dan komunikasi dengan pengguna'],
                ['Hardware & Peripheral', 'Diagnostik PC/laptop, printer, jaringan dasar'],
                ['OS & Software', 'Install, update, imaging, dan troubleshooting umum'],
                ['Jaringan Kantor', 'IP addressing dasar, Wi-Fi, printer sharing'],
                ['Keamanan Endpoint', 'Antivirus/EDR, patching, hardening ringan'],
                ['Ticketing & SLA', 'Mengelola tiket, prioritas, SLA, dan dokumentasi'],
                ['Backup & Recovery', 'Strategi backup user dan pemulihan cepat'],
                ['Monitoring & Reporting', 'Checklist harian, log perubahan, laporan mingguan'],
            ]),

            'Web Development' => $this->buildTemplate([
                ['Fundamental Web & Git', 'HTTP, arsitektur web, dan workflow Git'],
                ['HTML Semantik', 'Struktur dokumen, aksesibilitas dasar'],
                ['CSS Layout', 'Flexbox, Grid, responsive, utility dasar'],
                ['JavaScript Dasar', 'Variabel, fungsi, DOM, event'],
                ['Asinkron & API', 'Fetch/axios, JSON, error handling'],
                ['Backend Ringkas', 'RESTful patterns, auth sederhana (JWT/session)'],
                ['Database & Query', 'CRUD, relasi dasar, indexing ringan'],
                ['Deployment & Monitoring', 'Build, env, logging, dan observasi ringan'],
            ]),
        ];
    }

    /**
     * Bangun template modul lengkap dengan materi & video singkat.
     * Setiap modul diberi 2 materi dan 1 video placeholder.
     */
    private function buildTemplate(array $modules): array
    {
        $result = [];
        foreach ($modules as $idx => [$title, $desc]) {
            $week = $idx + 1;
            $result[] = [
                'judul' => "Minggu {$week}: {$title}",
                'deskripsi' => $desc,
                'materi' => [
                    [
                        'judul' => "$title - Konsep", 
                        'deskripsi' => "Penjelasan konsep utama untuk {$title}.",
                        'konten' => "<p>Ringkasan konsep untuk {$title}. Sertakan contoh praktis dan checklist singkat.</p>",
                    ],
                    [
                        'judul' => "$title - Praktik", 
                        'deskripsi' => "Langkah praktik / studi kasus untuk {$title}.",
                        'konten' => "<ul><li>Langkah praktik harian</li><li>Mini task mingguan</li><li>Catatan kendala umum</li></ul>",
                    ],
                ],
                'video' => [
                    'judul' => "Video Minggu {$week}: {$title}",
                    'deskripsi' => "Penjelasan singkat dan demo untuk {$title}.",
                    'file_video' => "videos/minggu-{$week}-" . str_replace(' ', '-', strtolower($title)) . ".mp4",
                ],
            ];
        }

        return $result;
    }

    /**
     * Template generik jika kursus tidak ada di daftar khusus.
     */
    private function genericTemplate(string $courseTitle): array
    {
        $modules = [];
        $topics = [
            'Pengenalan & Tujuan',
            'Fondasi Teori',
            'Tools & Lingkungan Kerja',
            'Praktik Dasar',
            'Studi Kasus Ringan',
            'Optimasi & Best Practice',
            'Evaluasi & Umpan Balik',
            'Presentasi Akhir',
        ];

        foreach ($topics as $idx => $title) {
            $modules[] = ["Minggu " . ($idx + 1) . ": {$title}", "Pembahasan {$title} untuk kursus {$courseTitle}."];
        }

        return $this->buildTemplate($modules);
    }
}
