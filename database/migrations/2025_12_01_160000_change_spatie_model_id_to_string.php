<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengubah model_id di tabel Spatie Permission menjadi string untuk support string ID
     */
    public function up(): void
    {
        // Skip on sqlite (testing) because ALTER/MODIFY syntax not supported
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        // Ubah model_has_roles.model_id menjadi string
        DB::statement('ALTER TABLE model_has_roles MODIFY model_id VARCHAR(10) NOT NULL');
        
        // Ubah model_has_permissions.model_id menjadi string (jika ada data)
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id VARCHAR(10) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }
        DB::statement('ALTER TABLE model_has_roles MODIFY model_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE model_has_permissions MODIFY model_id BIGINT UNSIGNED NOT NULL');
    }
};
