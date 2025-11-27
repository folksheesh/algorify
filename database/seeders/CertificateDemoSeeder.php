<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kursus;
use App\Models\Sertifikat;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * CertificateDemoSeeder
 *
 * This is a small, standalone seeder you can run locally to create
 * a single demo certificate record (and the demo user + kursus if missing).
 * It is intentionally isolated so it won't affect other seeds; run it with:
 *
 * php artisan db:seed --class=CertificateDemoSeeder
 */
class CertificateDemoSeeder extends Seeder
{
    public function run()
    {
        $nomor = 'CERT-ALG-2025-001234';

        // ensure we create related demo records idempotently

        // Demo user
        $user = User::firstOrCreate(
            ['email' => 'demo+cert@example.com'],
            [
                'name' => 'Prashant Kumar Singh',
                'password' => Hash::make('password'),
            ]
        );

        // Demo kursus
        $kursus = Kursus::firstOrCreate(
            ['judul' => 'Desain UI/UX (Demo)'],
            [
                'deskripsi' => 'Kursus demo untuk verifikasi sertifikat lokal',
                // allowed enum values for kursus.status are: draft | published | archived
                'status' => 'published',
                'kategori' => 'design',
                // kursus.user_id is not nullable in many schemas — attach the demo user
                'user_id' => $user->id,
            ]
        );

        // ensure kursus tanggal_selesai exists so the UI can show it
        $kursus->update(['tanggal_selesai' => '2025-01-20']);

        // create a demo enrollment (gives us nilai_akhir to show on the verification UI)
        $enrollmentModel = '\\App\\Models\\Enrollment';
        if (! $enrollmentModel::where('user_id', $user->id)->where('kursus_id', $kursus->id)->exists()) {
            $enrollmentModel::create([
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
                'kode' => 'ENR-DEMO-' . now()->timestamp,
                'status' => 'completed',
                'progress' => 100,
                'nilai_akhir' => 85,
            ]);
        }

        // Create demo sertifikat (explicit nomor so verification always finds it)
        $sertifikat = Sertifikat::firstOrCreate(
            ['nomor_sertifikat' => $nomor],
            [
                'user_id' => $user->id,
                'kursus_id' => $kursus->id,
                'judul' => 'Sertifikat Desain UI/UX',
                'deskripsi' => 'Demo sertifikat untuk alur verifikasi',
                'tanggal_terbit' => Carbon::parse('2025-01-20'),
                'status_sertifikat' => 'active',
                'file_path' => null,
            ]
        );

        if ($sertifikat->wasRecentlyCreated) {
            $this->command->info("Demo certificate created: {$nomor}");
        } else {
            $this->command->info("Demo certificate already present: {$nomor}");
        }
        $this->command->info("Try: /verifikasi-sertifikat?q={$nomor}");
    }
}
