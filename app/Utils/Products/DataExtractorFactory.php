<?php

namespace App\Utils\Products;

use App\Enums\Products\DataSource;
use App\Interfaces\Products\DataExtractorInterface;
use App\Models\Products\Product;
use App\Strategies\Products\ApiStrategy;
use App\Strategies\Products\CsvStrategy;

class DataExtractorFactory
{

    /**
     * Factory method to get the appropriate extractor based on the source type.
     *
     * @param DataSource $source
     * @param string|null $path
     * @param string|null $apiUrl
     * @return DataExtractorInterface
     */
    public static function getExtractor(DataSource $source, ?string $path = null, ?string $apiUrl = null): DataExtractorInterface
    {
        return match ($source) {
            DataSource::CSV => new CsvStrategy($path),
            DataSource::API => new ApiStrategy($apiUrl),
            default => throw new \InvalidArgumentException('Invalid source type'),
        };
    }

}
