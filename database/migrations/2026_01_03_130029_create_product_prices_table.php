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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained("products")->onDelete('cascade');
            $table->foreignId('currency_id')->constrained("currencies")->onDelete('restrict');
            $table->decimal('amount', 12, 2)->default(0);
            $table->unique(['product_id', 'currency_id'],'unique_product_currency');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
