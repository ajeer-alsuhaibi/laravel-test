<?php

namespace App\Services\Products;

use App\Enums\Products\DataSource;
use App\Interfaces\Products\DataExtractorInterface;
use App\Utils\Products\DataExtractorFactory;

class ProductSyncService
{
    private ProductService $productService;
    private DataExtractorInterface $dataExtractor;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Set the data extractor strategy (CSV or API).
     */
    public function setExtractor(DataExtractorInterface $extractor)
    {
        $this->dataExtractor = $extractor;
    }

    /**
     * Sync products from the given data source.
     * Accept source as DataSource enum, along with dynamic path or API URL.
     */
    public function syncProducts(DataSource $source, ?string $path = null, ?string $apiUrl = null)
    {
        $this->setExtractor(DataExtractorFactory::getExtractor($source, $path, $apiUrl));

        // Use chunking to handle large data efficiently
        $this->dataExtractor->extract()->chunk(1000)->each(function ($chunk) {
            $this->processChunk($chunk);
        });
    }

    /**
     * Process a chunk of products and save them.
     */
    private function processChunk($chunk)
    {
        foreach ($chunk as $dto) {
            $this->productService->saveProduct($dto);
        }
    }

}
