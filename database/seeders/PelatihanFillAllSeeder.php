<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Video;

class PelatihanFillAllSeeder extends Seeder
{
    /**
     * Isi modul/materi/video dummy untuk SEMUA kursus yang masih kosong modulnya.
     * Meniru struktur sederhana (8 modul, tiap modul 2 materi + 1 video).
     */
    public function run(): void
    {
        $courses = Kursus::orderBy('id')->get();

        if ($courses->isEmpty()) {
            $this->command->warn('Tidak ada kursus untuk diisi modul.');
            return;
        }

        $filled = 0;

        foreach ($courses as $kursus) {
            if ($kursus->modul()->exists()) {
                continue; // sudah punya konten, lewati
            }

            $template = $this->genericTemplate($kursus->judul);

            DB::transaction(function () use ($kursus, $template) {
                foreach ($template as $index => $mod) {
                    $modul = Modul::create([
                        'kursus_id' => $kursus->id,
                        'judul' => $mod['judul'],
                        'deskripsi' => $mod['deskripsi'],
                        'urutan' => $index + 1,
                    ]);

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

            $filled++;
        }

        $this->command->info("✓ PelatihanFillAllSeeder: modul/materi/video dibuat untuk {$filled} kursus yang sebelumnya kosong.");
    }

    private function genericTemplate(string $courseTitle): array
    {
        $topics = [
            'Pengenalan & Tujuan',
            'Dasar Teori',
            'Tools & Setup',
            'Praktik Inti',
            'Studi Kasus',
            'Optimasi & Best Practice',
            'Evaluasi & Umpan Balik',
            'Presentasi / Demo',
        ];

        $modules = [];
        foreach ($topics as $idx => $title) {
            $week = $idx + 1;
            $safeTitle = Str::slug($title, '-');
            $modules[] = [
                'judul' => "Minggu {$week}: {$title}",
                'deskripsi' => "Bahasan {$title} untuk kursus {$courseTitle}.",
                'materi' => [
                    [
                        'judul' => "{$title} - Konsep",
                        'deskripsi' => "Ringkasan konsep utama {$title}.",
                        'konten' => "<p>Catatan konsep singkat untuk {$title} pada kursus {$courseTitle}. Tambahkan contoh dan poin penting.</p>",
                    ],
                    [
                        'judul' => "{$title} - Praktik",
                        'deskripsi' => "Langkah praktik {$title}.",
                        'konten' => "<ul><li>Step-by-step tugas</li><li>Checklist hasil</li><li>Catatan troubleshooting</li></ul>",
                    ],
                ],
                'video' => [
                    'judul' => "Video {$title}",
                    'deskripsi' => "Penjelasan dan demo singkat {$title}.",
                    'file_video' => "videos/{$safeTitle}.mp4",
                ],
            ];
        }

        return $modules;
    }
}
