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
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('order_id' , 40)->unique();
            $table->date('order_date')->nullable(false);
            $table->enum('payment_status' , ['pending' , 'paid' , 'failed' , 'refunding'])->default('pending');
            $table->enum('status' , ['pending' , 'delivered' , 'success' , 'cancelled' , 'completed' , 'packaged'])->default('pending');
            $table->integer('total_payment')->default(0);
            $table->integer('total_products')->default(0);
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
