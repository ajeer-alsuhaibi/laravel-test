<?php

namespace App\Interfaces\Products;


use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

interface DataExtractorInterface {
    public function extract():LazyCollection|Collection;
}
