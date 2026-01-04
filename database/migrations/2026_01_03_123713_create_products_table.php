<?php

use App\Enums\Products\ProductStatus;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku',64);
            $table->enum('status',array_column(ProductStatus::cases(),'value'))->default(ProductStatus::Active->value);
            $table->unique('sku'); // Unique constraint on sku
            $table->index('status'); // Index on status for performance
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
