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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('start_at');
            $table->string('end_at');
            $table->enum('discount_type' , ['fixed' , 'percentage']) ;
            $table->decimal('discount_value') ;
            $table->integer('max_uses');
            $table->integer('used_count')->default(0);;
            $table->decimal('min_order_amount')->nullable(); // بمعني كوبون لايطبق علي مثلا منتجات كلها سعره يصل 100 جنية فق\ في ده مش هيطبق
            $table->enum('status' , ['active' , 'inactive'])->default('active') ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
