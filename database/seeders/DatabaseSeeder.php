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

        // Seed kursus
        $this->call([
            KursusSeeder::class,
        ]);

        // Seed enrollments
        $this->call([
            EnrollmentSeeder::class,
        ]);
    }
}
