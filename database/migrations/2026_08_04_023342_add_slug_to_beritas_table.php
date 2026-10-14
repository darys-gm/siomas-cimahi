<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom slug hanya jika belum ada
        if (!Schema::hasColumn('beritas', 'slug')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->string('slug')->unique()->nullable()->after('judul');
            });
        }

        // 2. Update data existing dengan slug (hanya yang masih kosong)
        $beritas = DB::table('beritas')->whereNull('slug')->orWhere('slug', '')->get();
        foreach ($beritas as $berita) {
            $slug = Str::slug($berita->judul);
            $originalSlug = $slug;
            $counter = 1;

            // Pastikan slug unik
            while (DB::table('beritas')->where('slug', $slug)->where('id', '!=', $berita->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            DB::table('beritas')
                ->where('id', $berita->id)
                ->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('beritas', 'slug')) {
            Schema::table('beritas', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};