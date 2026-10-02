<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn(['nomor_registrasi', 'nomor_sk', 'tahun_berdiri', 'website', 'deskripsi']);
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            $table->string('nomor_registrasi')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->string('website')->nullable();
            $table->text('deskripsi')->nullable();
        });
    }
};