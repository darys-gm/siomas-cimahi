<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->string('user_agent', 255)->nullable();
            $table->date('visit_date')->index();
            $table->timestamp('first_visit_at')->useCurrent();
            $table->timestamp('last_visit_at')->useCurrent();
            $table->integer('total_visits')->default(1);
            $table->timestamps();

            // 1 IP hanya tercatat 1x per hari
            $table->unique(['ip_address', 'visit_date'], 'unique_ip_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};