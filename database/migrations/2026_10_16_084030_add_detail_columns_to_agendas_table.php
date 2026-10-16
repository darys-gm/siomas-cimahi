<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom detail ke tabel agendas.
     * Idempotent — aman dijalankan berulang kali.
     * TANPA kolom user_id (admin hanya 1).
     */
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            if (!Schema::hasColumn('agendas', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('agendas', 'waktu')) {
                $table->string('waktu', 50)->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('agendas', 'tempat')) {
                $table->string('tempat', 255)->nullable()->after('waktu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            if (Schema::hasColumn('agendas', 'tempat')) {
                $table->dropColumn('tempat');
            }
            if (Schema::hasColumn('agendas', 'waktu')) {
                $table->dropColumn('waktu');
            }
            if (Schema::hasColumn('agendas', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }
};