<?php

namespace App\Models\Products;

use App\Models\Currencies\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'currency_id',
        'amount',
    ];

    /**
     * Get the product that owns the price.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the currency that the price is in.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
