<?php

namespace App\Services\Products;

use App\DTOs\Products\ProductDTO;
use App\Enums\Locales\SupportedLocales;
use App\Enums\Products\ProductStatus;
use App\Models\Products\Product;
use App\Models\Products\ProductTranslation;
use App\Models\Products\ProductVariant;
use App\Resolvers\ProductStatusResolver;
use App\Transformers\Products\ProductTransformer;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(
        private ProductTransformer $transformer,
        private ProductStatusResolver $statusResolver
    ) {}

    public function saveProduct(ProductDTO $dto, string $locale = SupportedLocales::Arabic->value): void
    {
        DB::transaction(function () use ($dto, $locale) {

            $productData = $this->transformer->transform($dto);

            // Resolve status (store/ensure in DB catalog if you use product_statuses)
            $statusRaw = $productData['status'] ?? $dto->status ?? null;
            $statusRaw = is_string($statusRaw) ? trim($statusRaw) : $statusRaw;
            $status = $this->statusResolver->resolve($statusRaw) ?? ProductStatus::Active->value;

            // Upsert product (ONLY columns that exist in `products`
            $product = Product::withTrashed()->updateOrCreate(
                ['sku' => $productData['sku']],
                [
                    'status' => $status,
                ]
            );

            // If deleted soft delete and stop
            if ($this->statusResolver->isDeleted($status)) {
                if (!$product->trashed()) {
                    $product->deleted_reason = 'Deleted due to synchronization (source status=deleted)';
                    $product->delete();
                }
                return;
            }

            //  Restore if previously deleted
            if ($product->trashed()) {
                $product->restore();
                $product->deleted_reason = null;
                $product->save();
            }

            // 4) Translation upsert (name goes to translations)
            ProductTranslation::updateOrCreate(
                ['product_id' => $product->id, 'locale' => $locale],
                ['name' => $productData['name'] ?? $dto->name]
            );

            $variations = $productData['variations'] ?? $dto->variations ?? [];

            if (is_string($variations)) {
                $decoded = json_decode($variations, true);
                $variations = is_array($decoded) ? $decoded : [];
            }

            if (!is_array($variations)) {
                $variations = [];
            }

            $this->handleVariants(
                product: $product,
                variations: $productData['variations'] ?? $dto->variations ?? [],
                status: $status,
                quantity: $productData['quantity'] ?? $dto->quantity
            );
        });
    }

    private function handleVariants(
        Product $product,
        array $variations,
        ?string $status,
        ?int $quantity
    ): void {
        $qty = $quantity ?? 0;

        if (empty($variations)) {
            ProductVariant::updateOrCreate(
                ['sku' => $product->sku . '-default'],
                [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'is_available' => $qty > 0,
                    'status' => $status,
                ]
            );
            return;
        }

        foreach ($variations as $variation) {
            $name = $variation['name'] ?? 'option';
            $variantSku = $product->sku . '-' . strtolower(preg_replace('/\s+/', '-', $name));

            ProductVariant::updateOrCreate(
                ['sku' => $variantSku],
                [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'is_available' => $qty > 0,
                    'status' => $status,
                ]
            );
        }
    }
}
