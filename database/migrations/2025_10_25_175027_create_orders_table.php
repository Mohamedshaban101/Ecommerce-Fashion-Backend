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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->double('subtotal' , 8,2);
            $table->double('total' , 8, 2);
            $table->double('shipping' , 8, 2);
            $table->double('discount' , 8,2);
            $table->enum('payment_status' , ['paid' , 'unpaid'])->default('unpaid');
            $table->enum('payment_method' , ['stripe' , 'cod'])->default('stripe');
            $table->enum('status' , ['pending' , 'shipped' , 'delivered' , 'cancelled'])->default('pending');
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
