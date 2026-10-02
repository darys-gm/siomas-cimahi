<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            if (!Schema::hasColumn('ormas', 'jumlah_anggota')) {
                $table->integer('jumlah_anggota')->nullable()->default(0)->after('alamat_kesekretariatan');
            }
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            if (Schema::hasColumn('ormas', 'jumlah_anggota')) {
                $table->dropColumn('jumlah_anggota');
            }
        });
    }
};