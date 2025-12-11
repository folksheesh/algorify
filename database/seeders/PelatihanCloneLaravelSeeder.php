<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Video;
use App\Models\Materi;
use App\Models\Ujian;
use App\Models\Soal;
use App\Models\PilihanJawaban;

class PelatihanCloneLaravelSeeder extends Seeder
{
    /**
     * Clone struktur modul/materi/video/quiz/ujian dari Fullstack Laravel Development ke semua kursus lain yang belum punya modul.
     */
    public function run(): void
    {
        $templateCourse = Kursus::where('judul', 'Fullstack Laravel Development')->first();
        if (!$templateCourse) {
            $this->command->error('Kursus Fullstack Laravel Development tidak ditemukan.');
            return;
        }

        $modulTemplate = Modul::where('kursus_id', $templateCourse->id)->orderBy('urutan')->get();
        if ($modulTemplate->isEmpty()) {
            $this->command->error('Modul template tidak ditemukan di Fullstack Laravel Development.');
            return;
        }

        $targetCourses = Kursus::where('id', '!=', $templateCourse->id)->get();
        $filled = 0;
        foreach ($targetCourses as $kursus) {
            if ($kursus->modul()->exists()) continue;

            foreach ($modulTemplate as $modul) {
                $newModul = Modul::create([
                    'kursus_id' => $kursus->id,
                    'judul' => $modul->judul,
                    'deskripsi' => $modul->deskripsi,
                    'urutan' => $modul->urutan,
                ]);

                // Clone materi
                foreach ($modul->materi as $materi) {
                    Materi::create([
                        'modul_id' => $newModul->id,
                        'judul' => $materi->judul,
                        'deskripsi' => $materi->deskripsi,
                        'konten' => $materi->konten,
                        'urutan' => $materi->urutan,
                    ]);
                }

                // Clone video
                foreach ($modul->video as $video) {
                    Video::create([
                        'modul_id' => $newModul->id,
                        'judul' => $video->judul,
                        'deskripsi' => $video->deskripsi,
                        'file_video' => $video->file_video,
                        'urutan' => $video->urutan,
                    ]);
                }

                // Clone ujian/quiz
                foreach ($modul->ujian as $ujian) {
                    $newUjian = Ujian::create([
                        'kursus_id' => $kursus->id,
                        'modul_id' => $newModul->id,
                        'judul' => $ujian->judul,
                        'deskripsi' => $ujian->deskripsi,
                        'tipe' => $ujian->tipe,
                        'waktu_pengerjaan' => $ujian->waktu_pengerjaan,
                        'minimum_score' => $ujian->minimum_score,
                    ]);

                    // Clone soal dan pilihan
                    foreach ($ujian->soal as $soal) {
                        $newSoal = Soal::create([
                            'kursus_id' => $kursus->id,
                            'ujian_id' => $newUjian->id,
                            'pertanyaan' => $soal->pertanyaan,
                            'tipe_soal' => $soal->tipe_soal,
                            'kunci_jawaban' => $soal->kunci_jawaban,
                            'pembahasan' => $soal->pembahasan,
                        ]);
                        foreach ($soal->pilihanJawaban as $pilihan) {
                            PilihanJawaban::create([
                                'soal_id' => $newSoal->id,
                                'pilihan' => $pilihan->pilihan,
                                'is_correct' => $pilihan->is_correct,
                            ]);
                        }
                    }
                }
            }
            $filled++;
        }
        $this->command->info("✓ Struktur pelatihan Laravel berhasil diclone ke {$filled} kursus lain.");
    }
}