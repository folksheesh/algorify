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
        // Seed roles
        $this->call([
            RolePesertaSeeder::class,
            RoleAdminSeeder::class,
            RolePengajarSeeder::class,
            RoleSuperAdminSeeder::class,
        ]);

        // Optional: example user seeding can be kept or removed
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
