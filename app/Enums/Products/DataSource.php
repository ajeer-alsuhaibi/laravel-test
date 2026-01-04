<?php

namespace App\Enums\Products;

enum DataSource: string
{
    case CSV = 'csv';
    case API = 'api';
}
