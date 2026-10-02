<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Tambahkan kolom pelaporan setelah nomor_registrasi (sesuai struktur tabel)
            $table->enum('pelaporan', ['sudah', 'belum', 'tidak_ada'])->nullable()->default('belum')->after('nomor_registrasi');
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            $table->dropColumn('pelaporan');
        });
    }
};