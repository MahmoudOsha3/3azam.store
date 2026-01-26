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
            $table->foreignId('user_id')->references('id')->on('users')->restrictOnDelete() ;
            $table->string('number_order')->unique();
            $table->float('delivery_fee')->default(0.0) ;
            $table->float('subtotal');
            $table->float('total_price');
            $table->enum('status' ,
                ['pending' , 'processing' , 'delivering' , 'completed' , 'cancelled'])->default('pending');
            $table->enum('type_payment' , ['cashOnDelivery' , 'wallet']);
            $table->enum('payment_status' , ['unpaid' , 'paid' , 'failed'])->default('unpaid');
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
