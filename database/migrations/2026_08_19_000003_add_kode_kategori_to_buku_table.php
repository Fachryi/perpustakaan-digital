<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->string('kode_buku', 20)->unique()->nullable()->after('id');
            $table->unsignedBigInteger('kategori_id')->nullable()->after('jenis_id');
            $table->foreign('kategori_id')->references('id')->on('kategori')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn(['kode_buku', 'kategori_id']);
        });
    }
};
