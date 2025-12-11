<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Kursus;
use App\Models\Modul;
use App\Models\Materi;
use App\Models\Video;

class PelatihanRemainingSeeder extends Seeder
{
    /**
     * Seed pelatihan untuk sisa 3/4 kursus (semua kecuali batch pertama).
     * Setiap kursus mendapat 8 modul (6-10 minggu) dengan materi & video ringkas.
     */
    public function run(): void
    {
        $totalKursus = Kursus::count();
        if ($totalKursus === 0) {
            $this->command->warn('Tidak ada kursus. Jalankan KursusSeeder terlebih dahulu.');
            return;
        }

        $skipCount = max(0, (int) ceil($totalKursus / 4));
        // MySQL: skip() but must use take() or limit() to avoid 'offset' error
        $courses = Kursus::orderBy('id')->skip($skipCount)->take(PHP_INT_MAX)->get();

        if ($courses->isEmpty()) {
            $this->command->warn('Tidak ada kursus tersisa untuk di-seed oleh PelatihanRemainingSeeder.');
            return;
        }

        $courseIds = $courses->pluck('id');

        // Bersihkan modul/materi/video untuk kursus target agar tidak duplikat
        DB::transaction(function () use ($courseIds) {
            $modulIds = Modul::whereIn('kursus_id', $courseIds)->pluck('id');
            Video::whereIn('modul_id', $modulIds)->delete();
            Materi::whereIn('modul_id', $modulIds)->delete();
            Modul::whereIn('id', $modulIds)->delete();
        });

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
        }

        $this->command->info('✓ PelatihanRemainingSeeder: modul/materi/video dibuat untuk ' . $courses->count() . ' kursus (sisa 3/4).');
    }

    // Template untuk kursus populer
    private function moduleTemplates(): array
    {
        return [
            'Mobile Development' => $this->buildTemplate([
                ['Pengenalan Mobile & UI', 'Dasar platform, pattern mobile, dan prinsip UI mobile'],
                ['Dart/JS Dasar', 'Sintaks dasar (Dart/JS) untuk framework cross-platform'],
                ['State Management', 'Stateful widget / hooks / simple state mgmt'],
                ['Layout & Navigasi', 'Stacking layout, responsive, dan routing/navigation'],
                ['Networking & API', 'HTTP client, JSON parsing, error handling'],
                ['Storage Lokal', 'Shared prefs/secure storage, caching ringan'],
                ['Integrasi Fitur Device', 'Camera, location, notifikasi dasar'],
                ['Testing & Release', 'Widget test/unit, signing, dan publishing'],
            ]),

            'Digital Marketing' => $this->buildTemplate([
                ['Dasar Digital Marketing', 'Funnel, KPI, channel overview'],
                ['Riset Audiens & Persona', 'Segmentasi, persona, dan value prop'],
                ['Content Strategy', 'Calendar, copywriting ringkas, creative brief'],
                ['SEO Dasar', 'Keyword, on-page, technical ringan'],
                ['Paid Ads Ringkas', 'FB/IG/Google Ads dasar, budget & bidding'],
                ['Analytics & Tracking', 'UTM, basic GA, event tracking'],
                ['Optimization', 'A/B testing ringan, iterasi campaign'],
                ['Reporting', 'Dashboard KPI dan rekomendasi perbaikan'],
            ]),

            'AI & Machine Learning' => $this->buildTemplate([
                ['Math & Python Refresher', 'Aljabar linear, probabilitas, Python data stack'],
                ['Data Prep & Pipeline', 'Cleaning, split, pipeline sederhana'],
                ['Model Klasik', 'Regresi, tree, ensemble ringkas'],
                ['Model Evaluasi', 'Metrik, CV, bias-variance'],
                ['Feature Engineering', 'Encoding, scaling, selection'],
                ['Intro Deep Learning', 'ANN dasar, overfit & regularisasi'],
                ['MLOps Ringan', 'Versioning data/model, reproducibility'],
                ['Deploy & Monitoring', 'Serving sederhana dan observability dasar'],
            ]),

            'Cloud Computing' => $this->buildTemplate([
                ['Cloud Fundamentals', 'Model layanan, region/zone, shared responsibility'],
                ['Identity & Access', 'IAM dasar, peran, dan best practice'],
                ['Compute & Container', 'VM, autoscale, container service ringkas'],
                ['Storage & Database', 'Object/block, managed DB, backup snapshot'],
                ['Networking', 'VPC/VNet, subnet, security group, LB'],
                ['Observability', 'Logging, metric, alert dasar'],
                ['IaC Ringan', 'Template/terraform ringkas, env separation'],
                ['Cost & Reliability', 'Tagging, budgeting, HA/DR dasar'],
            ]),

            'Blockchain Development' => $this->buildTemplate([
                ['Fundamental Blockchain', 'Konsep ledger, konsensus, dan blok'],
                ['Ethereum & Tools', 'Node, wallet, dan toolchain'],
                ['Smart Contract Dasar', 'Solidity dasar, struktur contract'],
                ['Testing & Security', 'Unit test, pitfalls umum'],
                ['DApp Frontend', 'Integrasi wallet, web3 library'],
                ['Token & NFT', 'ERC-20/721 ringkas, use case'],
                ['Gas & Optimasi', 'Biaya gas, optimasi sederhana'],
                ['Deploy & Monitoring', 'Testnet/mainnet, verifikasi, monitor'],
            ]),

            'DevOps Engineering' => $this->buildTemplate([
                ['Culture & GitFlow', 'DevOps mindset, branching, PR flow'],
                ['CI Basics', 'Pipeline build/test lint sederhana'],
                ['Artifact & Registry', 'Build artifacts, container registry'],
                ['CD & Environments', 'Deploy staging/prod, rollout strategi'],
                ['Observability', 'Log/metric/tracing dasar'],
                ['IaC & Config', 'Template infra, config as code'],
                ['Security Shift-Left', 'SAST/DAST ringkas, secrets mgmt'],
                ['Reliability', 'Rollback, backup, dan DR drill ringan'],
            ]),

            'Data Visualization' => $this->buildTemplate([
                ['Prinsip Visual', 'Gestalt, chart selection, storytelling'],
                ['Tools Setup', 'Tableau/Power BI setup'],
                ['Data Prep', 'Clean/shape data untuk visual'],
                ['Core Charts', 'Bar/line/pie dengan best practice'],
                ['Interaktivitas', 'Filter, drilldown, tooltip'],
                ['Dashboarding', 'Layout, hierarki, CTA'],
                ['Publishing', 'Share, embed, permission'],
                ['Insight Delivery', 'Narrative, anotasi, dan rekomendasi'],
            ]),
        ];
    }

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
