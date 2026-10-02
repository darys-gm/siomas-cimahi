<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->text('alamat')->nullable()->after('jabatan');
        });
    }

    public function down()
    {
        Schema::table('pengurus', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};