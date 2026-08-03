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
        Schema::create('file_bukus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buku_id');
            $table->foreign('buku_id')->references('id')->on('buku')->cascadeOnDelete();
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_type')->default('pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_bukus');
    }
};
