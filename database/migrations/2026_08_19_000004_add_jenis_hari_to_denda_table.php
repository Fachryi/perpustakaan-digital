<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('denda', function (Blueprint $table) {
            $table->enum('jenis_denda', ['keterlambatan', 'kehilangan'])->default('keterlambatan')->after('keterangan');
            $table->integer('hari_terlambat')->default(0)->after('jenis_denda');
        });
    }

    public function down(): void
    {
        Schema::table('denda', function (Blueprint $table) {
            $table->dropColumn(['jenis_denda', 'hari_terlambat']);
        });
    }
};
