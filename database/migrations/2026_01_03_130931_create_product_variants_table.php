<?php

use App\Enums\Products\ProductVariantStatus;
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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('sku');
            $table->enum('status', array_column(ProductVariantStatus::cases(),'value'))->default(ProductVariantStatus::Active->value);  // ENUM for variant status (e.g., 'active', 'inactive', 'out_of_stock')
            $table->timestamps();

            $table->unique('sku', 'unique_variant_sku');
            $table->index('product_id', 'index_variant_product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
