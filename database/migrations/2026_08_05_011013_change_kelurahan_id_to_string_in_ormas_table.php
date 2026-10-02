<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ormas', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['kelurahan_id']);
            // Ubah tipe data menjadi string
            $table->string('kelurahan_id', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('ormas', function (Blueprint $table) {
            $table->unsignedBigInteger('kelurahan_id')->nullable()->change();
            $table->foreign('kelurahan_id')->references('id')->on('kelurahan')->onDelete('set null');
        });
    }
};