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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('note')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('type_order_id');
            $table->foreignId('state_order_id')->default(1);
            $table->foreignId('promo_code_id')->nullable();
            $table->integer('total_preparation_time')->default(0);
            $table->integer('total_price')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
