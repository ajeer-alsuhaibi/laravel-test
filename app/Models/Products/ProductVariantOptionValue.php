<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOptionValue extends Model
{
    use HasFactory;

    protected $table = 'product_variant_values';
    protected $fillable = [
        'variant_id',
        'option_value_id',
    ];

    /**
     * Get the variant that owns the option value.
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    /**
     * Get the option value that belongs to the variant.
     */
    public function optionValue()
    {
        return $this->belongsTo(ProductOptionValue::class);
    }
}
