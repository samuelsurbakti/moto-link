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
        Schema::create('ml_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->string('code')->unique();
            $table->foreignUuid('category_id')->constrained('ml_product_categories')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_products');
    }
};
