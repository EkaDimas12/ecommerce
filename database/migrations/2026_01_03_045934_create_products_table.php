<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
  Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
    $table->string('name');
    $table->string('slug')->unique();
    $table->unsignedInteger('price'); // rupiah
    $table->text('description');
    $table->boolean('is_best_seller')->default(false);
    $table->unsignedInteger('stock')->nullable(); // opsional
    $table->string('main_image')->nullable(); // placeholder
    $table->timestamps();
  });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
