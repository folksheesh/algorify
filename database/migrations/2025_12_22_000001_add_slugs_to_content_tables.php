<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->addSlugColumn('kursus', 'judul');
        $this->addSlugColumn('modul', 'judul');
        $this->addSlugColumn('materi', 'judul');
        $this->addSlugColumn('video', 'judul');
        $this->addSlugColumn('ujian', 'judul');

        $this->backfillSlugs('kursus', 'judul');
        $this->backfillSlugs('modul', 'judul');
        $this->backfillSlugs('materi', 'judul');
        $this->backfillSlugs('video', 'judul');
        $this->backfillSlugs('ujian', 'judul');

        $this->addSlugUniqueIndex('kursus');
        $this->addSlugUniqueIndex('modul');
        $this->addSlugUniqueIndex('materi');
        $this->addSlugUniqueIndex('video');
        $this->addSlugUniqueIndex('ujian');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kursus', function (Blueprint $table) {
            $table->dropUnique('kursus_slug_unique');
            $table->dropColumn('slug');
        });

        Schema::table('modul', function (Blueprint $table) {
            $table->dropUnique('modul_slug_unique');
            $table->dropColumn('slug');
        });

        Schema::table('materi', function (Blueprint $table) {
            $table->dropUnique('materi_slug_unique');
            $table->dropColumn('slug');
        });

        Schema::table('video', function (Blueprint $table) {
            $table->dropUnique('video_slug_unique');
            $table->dropColumn('slug');
        });

        Schema::table('ujian', function (Blueprint $table) {
            $table->dropUnique('ujian_slug_unique');
            $table->dropColumn('slug');
        });
    }

    private function addSlugColumn(string $table, string $titleColumn): void
    {
        if (! Schema::hasColumn($table, 'slug')) {
            Schema::table($table, function (Blueprint $table) use ($titleColumn) {
                $table->string('slug')->nullable()->after($titleColumn);
            });
        }
    }

    private function addSlugUniqueIndex(string $table): void
    {
        $indexName = $table . '_slug_unique';
        $exists = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]);

        if (empty($exists)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->unique('slug', $indexName);
            });
        }
    }

    private function backfillSlugs(string $table, string $titleColumn): void
    {
        $rows = DB::table($table)->select('id', $titleColumn)->orderBy('id')->get();
        $used = [];

        foreach ($rows as $row) {
            $base = Str::slug($row->{$titleColumn} ?? '');
            if ($base === '') {
                $base = $table . '-' . $row->id;
            }

            $slug = $base;
            $suffix = 1;
            while (in_array($slug, $used, true) || DB::table($table)->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $suffix;
                $suffix++;
            }

            $used[] = $slug;
            DB::table($table)->where('id', $row->id)->update(['slug' => $slug]);
        }
    }
};
