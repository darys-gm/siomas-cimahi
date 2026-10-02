<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ormas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('nomor_registrasi')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->year('tahun_berdiri')->nullable();
            $table->foreignId('jenis_ormas_id')->nullable()->constrained('jenis_ormas')->onDelete('set null');
            $table->foreignId('bidang_kegiatan_id')->nullable()->constrained('bidang_kegiatan')->onDelete('set null');
            $table->text('alamat')->nullable();
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatan')->onDelete('set null');
            $table->foreignId('kelurahan_id')->nullable()->constrained('kelurahan')->onDelete('set null');
            $table->string('email')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('website')->nullable();
            $table->string('logo')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['draft', 'menunggu_verifikasi', 'revisi', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_revisi')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ormas');
    }
};