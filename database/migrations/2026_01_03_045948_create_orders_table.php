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
        $table->string('code')->unique();

        $table->string('customer_name');
        $table->string('phone');
        $table->string('email')->nullable();

        $table->text('address');
        $table->unsignedInteger('city_id')->nullable();
        $table->string('postal_code')->nullable();

        $table->string('courier')->nullable();
        $table->string('service')->nullable();
        $table->unsignedInteger('shipping_cost')->default(0);

        $table->unsignedInteger('subtotal')->default(0);
        $table->unsignedInteger('total')->default(0);

        // payment_method: transfer (midtrans) / cod
        $table->enum('payment_method', ['transfer', 'cod'])->default('transfer');

        // payment_status: pending/paid/cod/failed
        $table->enum('payment_status', ['pending', 'paid', 'cod', 'failed'])->default('pending');

        // order_status: new/processing/done/cancelled
        $table->enum('order_status', ['new', 'processing', 'done', 'cancelled'])->default('new');

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
