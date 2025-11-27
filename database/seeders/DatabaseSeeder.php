<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first
        $this->call([
            RolePesertaSeeder::class,
            RoleAdminSeeder::class,
            RolePengajarSeeder::class,
            RoleSuperAdminSeeder::class,
        ]);

        // Seed users
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
        ]);

        // Seed kategori
        $this->call([
            KategoriSeeder::class,
        ]);

        // Seed kursus
        $this->call([
            KursusSeeder::class,
        ]);

        // Seed Web Development content (Modul, Video, Materi, Ujian, Soal)
        $this->call([
            ModulSeeder::class,
            VideoSeeder::class,
            MateriSeeder::class,
            UjianSeeder::class,
            SoalSeeder::class,
            PilihanJawabanSeeder::class,
        ]);

        // Seed pelatihan lengkap (Laravel course dengan modul, video, bacaan, quiz, ujian)
        $this->call([
            PelatihanLengkapSeeder::class,
        ]);

        // Seed bank soal
        $this->call([
            BankSoalSeeder::class,
        ]);

        // Seed enrollments
        $this->call([
            EnrollmentSeeder::class,
        ]);
    }
}
