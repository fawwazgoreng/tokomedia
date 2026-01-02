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
        Schema::create('orderitems', function (Blueprint $table) {
            $table->id();
            $table->string('order_id' , 40);
            $table->foreignId('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->integer('price')->default(0);
            $table->integer('quantity')->default(0);
            $table->string('variants')->nullable(true);
            $table->integer('subtotal')->default(0);
            $table->timestamps();
            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderitems');
    }
};
