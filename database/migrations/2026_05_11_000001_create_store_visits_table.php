<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('store_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('sales_latitude', 10, 8);
            $table->decimal('sales_longitude', 11, 8);
            $table->float('sales_accuracy')->default(0);
            $table->float('distance_meters')->default(0);
            $table->enum('status', ['diterima', 'ditolak']);
            $table->timestamp('visited_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('store_visits');
    }
};
