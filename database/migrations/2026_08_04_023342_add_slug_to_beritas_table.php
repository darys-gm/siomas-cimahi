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
        // 1. Tambahkan kolom slug
        Schema::table('beritas', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('judul');
        });

        // 2. Update data existing dengan slug
        $beritas = DB::table('beritas')->get();
        foreach ($beritas as $berita) {
            DB::table('beritas')
                ->where('id', $berita->id)
                ->update(['slug' => Str::slug($berita->judul)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};