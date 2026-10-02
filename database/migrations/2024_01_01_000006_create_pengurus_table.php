<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ormas_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('nik', 20);
            $table->string('no_hp', 20)->nullable();
            $table->date('masa_jabatan_mulai')->nullable();
            $table->date('masa_jabatan_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengurus');
    }
};