<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('saran', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable(); // Opsional, bisa kosong
            $table->string('email')->nullable(); // Opsional
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca'])->default('baru');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('saran');
    }
};