<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
    ];

    /**
     * Get the option values for the option.
     */
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class);
    }
}
