<?php

namespace App\Transformers\Products;



use App\Enums\Products\Color;
use App\Enums\Products\Material;

class VariationTransformer
{
    /**
     * Transform color and material attributes to the expected format.
     */
    public function transform(array $apiVariation): array
    {
        $transformed = [];

        if (isset($apiVariation['color']) && in_array($apiVariation['color'],array_column(Color::cases(),'value'))) {
            $transformed[] = [
                'name' => 'Color',
                'value' => [Color::from($apiVariation['color'])->value]
            ];
        }

        if (isset($apiVariation['material']) && in_array($apiVariation['material'], array_column(Material::cases(),'value') )) {
            $transformed[] = [
                'name' => 'Material',
                'value' => [Material::from($apiVariation['material'])->value]
            ];
        }

        return $transformed;
    }
}
