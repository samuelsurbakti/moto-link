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
        Schema::create('ml_product_units', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id')->constrained('ml_products')->cascadeOnDelete();
            $table->foreignUuid('unit_id')->constrained('ml_units')->cascadeOnDelete();
            $table->decimal('purchase_price', 12, 2)->default(0); // Harga beli
            $table->decimal('selling_price', 12, 2)->default(0); // Harga jual
            $table->integer('conversion_factor')->default(1); // 1 Dus = 10 Pcs -> factor=10
            $table->boolean('is_default')->default(false); // Satuan default produk
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ml_product_units');
    }
};
