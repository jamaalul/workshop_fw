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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idpesanan');
            $table->string('transaction_id')->nullable(); // from MidTrans
            $table->string('payment_type', 50)->nullable(); // bank_transfer/echannel/qris
            $table->integer('gross_amount')->nullable();
            $table->string('payment_status', 50)->default('pending'); // pending/settlement/expire/cancel/deny
            $table->string('fraud_status', 50)->nullable(); // accept/challenge/deny
            $table->string('va_number', 255)->nullable(); // for VA payments
            $table->string('bank', 50)->nullable(); // bca/bni/bri/mandiri/permata/cimb
            $table->string('bill_key', 255)->nullable(); // for Mandiri Bill
            $table->string('biller_code', 50)->nullable(); // for Mandiri Bill
            $table->text('qr_code_url')->nullable(); // for QRIS
            $table->timestamp('expiry_time')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('midtrans_response')->nullable(); // Store full response
            $table->timestamps();

            $table->foreign('idpesanan')->references('idpesanan')->on('pesanan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
