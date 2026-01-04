<?php

namespace App\Enums\Products;

enum ProductStatus: String
{
    case Active='active';
    case Inactive='inactive';
    case Archived= 'archived';

    case Hidden = 'hidden';
    case Sale   = 'sale';
    case Deleted = 'deleted';

}
