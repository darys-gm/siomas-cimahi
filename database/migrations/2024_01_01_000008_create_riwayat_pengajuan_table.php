<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('riwayat_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ormas_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('riwayat_pengajuan');
    }
};