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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->string('email', 255)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->binary('photo_blob')->nullable();     // Blob storage (Tambah Customer 1)
            $table->string('photo_path', 500)->nullable(); // File path storage (Tambah Customer 2)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
