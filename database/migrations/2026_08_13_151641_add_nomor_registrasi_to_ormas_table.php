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
        Schema::table('ormas', function (Blueprint $table) {
            // Tambahkan kolom nomor_registrasi setelah kolom no_telepon
            $table->string('nomor_registrasi')->nullable()->after('no_telepon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Hapus kolom nomor_registrasi jika rollback
            $table->dropColumn('nomor_registrasi');
        });
    }
};