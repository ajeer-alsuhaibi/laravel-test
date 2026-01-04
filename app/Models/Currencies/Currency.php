<?php

namespace App\Models\Currencies;

use App\Models\Products\ProductPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'minor_unit',
    ];

    /**
     * Get the prices for the currency.
     */
    public function prices()
    {
        return $this->hasMany(ProductPrice::class);
    }
}
