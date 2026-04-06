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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idpesanan');
            $table->string('nama');
            $table->timestamp('timestamp')->useCurrent();
            $table->integer('total');
            $table->string('metode_bayar')->nullable();
            $table->smallInteger('status_bayar')->default(0); // 0=pending, 1=lunas, 2=expired, 3=cancelled
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
