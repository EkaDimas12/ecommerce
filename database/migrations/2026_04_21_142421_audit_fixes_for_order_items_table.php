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
        // Set invalid product_ids to null first
        \Illuminate\Support\Facades\DB::statement('
            UPDATE order_items 
            SET product_id = NULL 
            WHERE product_id IS NOT NULL 
            AND product_id NOT IN (SELECT id FROM products)
        ');

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
    }
};
