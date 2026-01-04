<?php

namespace App\Transformers\Products;

use App\DTOs\Products\ProductDTO;
use App\Models\Products\Product;

class ProductTransformer
{
    public function transform(ProductDTO $dto): array
    {
        $variations = $dto->variations ?? [];

        if (is_string($variations)) {
            $decoded = json_decode($variations, true);
            $variations = is_array($decoded) ? $decoded : [];
        }

        return [
            'sku' => $dto->sku,
            'name' => $dto->name,
            'status' => $dto->status,
            'price' => $dto->price,
            'currency' => $dto->currency,
            'quantity' => $dto->quantity ?? null,
            'variations' => $variations,
        ];
    }

}
