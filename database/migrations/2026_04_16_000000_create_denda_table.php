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
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_buku_id');
            $table->decimal('jumlah', 12, 2)->default(0);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->date('tanggal_bayar')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_buku_id')
                ->references('id')
                ->on('peminjaman_buku')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
