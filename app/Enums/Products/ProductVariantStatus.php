<?php

namespace App\Enums\Products;

enum ProductVariantStatus: string {

    case Active = 'Active';
    case Inactive = 'Inactive';
    case OutOfStock = 'out_of_stock';

}
