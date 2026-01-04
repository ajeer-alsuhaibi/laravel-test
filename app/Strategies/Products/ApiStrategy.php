<?php

namespace App\Strategies\Products;

use App\DTOs\Products\ProductDTO;
use App\Enums\Currencies\Currency;
use App\Enums\Products\ProductStatus;
use App\Interfaces\Products\DataExtractorInterface;
use App\Transformers\Products\VariationTransformer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\LazyCollection;
use SplFileObject;
use Illuminate\Support\Collection;

class ApiStrategy implements DataExtractorInterface
{
    private string $apiUrl;
    private int $limit;
    private int $page;
    private VariationTransformer $variationTransformer;
    public function __construct(string $apiUrl, VariationTransformer $variationTransformer,int $limit = 10, int $page = 1)
    {
        $this->apiUrl = $apiUrl;
        $this->variationTransformer = $variationTransformer;
        $this->limit = $limit;
        $this->page = $page;
    }
    public function extract(): LazyCollection
    {
        return LazyCollection::make(function () {
            // Keep fetching until there is no data left
            while (true) {
                $response = Http::get($this->apiUrl, [
                    'page' => $this->page,
                    'limit' => $this->limit,
                ]);

                $response->throw();

                $products = $response->json('data') ?? [];

                if (empty($products)) {
                    break;
                }

                foreach ($products as $item) {
                    yield $this->mapToProductDTO($item);
                }

                // Increment the page to get the next set of data
                $this->page++;
            }
        });
    }

    /**
     * Map an API response item to a ProductDTO.
     */
    private function mapToProductDTO(array $item): ProductDTO
    {
        return new ProductDTO(
            sku: (string) $item['id'],
            name: $item['name'],
            status: $this->mapStatus($item['isDeleted']),
            price: (float) $item['price'],
            currency: Currency::SAR->value,  // Default to USD
            variations: $this->transformVariations($item['variations'] ?? [])
        );
    }

    /**
     * Map the product's status based on the 'isDeleted' field from the API.
     */
    private function mapStatus(bool $isDeleted): ProductStatus
    {
        return $isDeleted ? ProductStatus::Archived : ProductStatus::Active;
    }

    /**
     * Transform API variations into the expected format for ProductService.
     */
    private function transformVariations(array $apiVariations): array
    {
        $transformed = [];

        foreach ($apiVariations as $variant) {
            $transformed = array_merge($transformed, $this->variationTransformer->transform($variant));
        }

        return $transformed;
    }


}
