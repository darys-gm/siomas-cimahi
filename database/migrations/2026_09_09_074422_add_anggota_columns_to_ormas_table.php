<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Cek dan tambahkan kolom jika belum ada
            if (!Schema::hasColumn('ormas', 'jumlah_anggota_perempuan')) {
                $table->integer('jumlah_anggota_perempuan')->nullable()->default(0)->after('jumlah_anggota');
            }
            if (!Schema::hasColumn('ormas', 'anggota_perempuan_rentang_16_30')) {
                $table->integer('anggota_perempuan_rentang_16_30')->nullable()->default(0)->after('jumlah_anggota_perempuan');
            }
            if (!Schema::hasColumn('ormas', 'jumlah_anggota_laki_laki')) {
                $table->integer('jumlah_anggota_laki_laki')->nullable()->default(0)->after('anggota_perempuan_rentang_16_30');
            }
            if (!Schema::hasColumn('ormas', 'anggota_laki_laki_rentang_16_30')) {
                $table->integer('anggota_laki_laki_rentang_16_30')->nullable()->default(0)->after('jumlah_anggota_laki_laki');
            }
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            $columns = [
                'jumlah_anggota_perempuan',
                'anggota_perempuan_rentang_16_30',
                'jumlah_anggota_laki_laki',
                'anggota_laki_laki_rentang_16_30'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('ormas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};