<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();

            // Order reference
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('order_code', 20)->index();

            // Event info
            $table->string('event', 50);           // created, payment_pending, payment_success, payment_failed, payment_expired, cancelled, status_changed, stock_restored
            $table->string('payment_type', 50)->nullable(); // bank_transfer, gopay, credit_card, etc
            $table->string('transaction_status', 50)->nullable(); // Midtrans raw status

            // Status snapshot
            $table->string('payment_status_from', 20)->nullable(); // Status sebelum
            $table->string('payment_status_to', 20)->nullable();   // Status sesudah
            $table->string('order_status_from', 20)->nullable();
            $table->string('order_status_to', 20)->nullable();

            // Additional data (JSON)
            $table->json('metadata')->nullable();   // raw Midtrans payload, etc

            // Who / where
            $table->string('source', 30);           // midtrans_webhook, checkout, admin, user_cancel
            $table->string('ip_address', 45)->nullable();

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
