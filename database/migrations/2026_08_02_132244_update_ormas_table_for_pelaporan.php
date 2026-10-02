<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Tambah kolom singkatan
            $table->string('singkatan')->nullable()->after('nama');
            // Ubah alamat menjadi alamat_kesekretariatan
            $table->renameColumn('alamat', 'alamat_kesekretariatan');
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            $table->dropColumn('singkatan');
            $table->renameColumn('alamat_kesekretariatan', 'alamat');
        });
    }
};