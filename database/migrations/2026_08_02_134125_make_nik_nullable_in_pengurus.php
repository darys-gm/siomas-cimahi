<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->string('nik', 20)->nullable(false)->change();
        });
    }
};