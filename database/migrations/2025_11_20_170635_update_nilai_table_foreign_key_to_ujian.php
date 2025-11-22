<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop old foreign key constraint
            $table->dropForeign(['kuis_id']);
            
            // Rename column
            $table->renameColumn('kuis_id', 'ujian_id');
            
            // Add new foreign key constraint
            $table->foreign('ujian_id')->references('id')->on('ujian')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            // Drop new foreign key constraint
            $table->dropForeign(['ujian_id']);
            
            // Rename column back
            $table->renameColumn('ujian_id', 'kuis_id');
            
            // Add old foreign key constraint
            $table->foreign('kuis_id')->references('id')->on('kuis')->onDelete('cascade');
        });
    }
};
