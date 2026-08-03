<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->foreign('kelas_id')->references('id')->on('kelas')->cascadeOnDelete();
            $table->string('judul');
            $table->text('sinopsis')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('pengarang')->nullable();
            $table->string('penerbit')->nullable();
            $table->string('tahun_terbit')->nullable();
            $table->unsignedBigInteger('jenis_id')->nullable();
            $table->foreign('jenis_id')->references('id')->on('jenis')->cascadeOnDelete();
            $table->integer('view')->default(0);
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
