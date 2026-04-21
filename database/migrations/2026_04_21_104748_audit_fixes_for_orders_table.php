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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('code')->constrained()->nullOnDelete();
        });

        // Modify ENUM using DB statement since Doctrine DBAL sometimes has issues with enums
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('new', 'processing', 'shipped', 'delivered', 'done', 'cancelled') DEFAULT 'new'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('new', 'processing', 'done', 'cancelled') DEFAULT 'new'");
        
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
