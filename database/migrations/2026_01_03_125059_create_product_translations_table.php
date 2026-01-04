<?php

use App\Enums\Locales\SupportedLocales;
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
        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('locale',10)->default(SupportedLocales::Arabic);
            $table->string('name'); //Translated name
            $table->string('description')->nullable();
            $table->unique(['product_id', 'locale'], 'unique_product_locale');
            $table->index('locale', 'index_product_locale');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_translations');
    }
};
