<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'option_id',
        'value_code',
    ];

    /**
     * The variants that belong to the option value.
     */
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_values');
    }

    /**
     * Get the option that owns the option value.
     */
    public function option()
    {
        return $this->belongsTo(ProductOption::class);
    }
}
