<?php

namespace App\DTOs\Products;

use App\Enums\Products\ProductStatus;

class ProductDTO{
    public string $sku;
    public string $name;
    public ? string $status;
    public ?float $price;
    public ?string $currency;

    public array $variations;

    public ?int $quantity = null;

    public function __construct(
        string $sku,
        string $name,
         ?string $status,
        ?float $price,
        ?string $currency,
        array $variations,
        ?int $quantity = null
    )
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->status = $status;
        $this->price = $price;
        $this->currency = $currency;
        $this->variations = $variations;
        $this->quantity = $quantity;


    }


}
