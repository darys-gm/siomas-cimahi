<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('ormas', function (Blueprint $table) {
        // Menambahkan kolom tahun_daftar setelah kolom nama (atau sesuaikan)
        // Menggunakan integer karena isinya tahun (contoh: 2023)
        $table->integer('tahun_daftar')->nullable()->after('nama');
    });
}

public function down()
{
    Schema::table('ormas', function (Blueprint $table) {
        $table->dropColumn('tahun_daftar');
    });
}
};
