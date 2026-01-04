<?php

namespace App\Strategies\Products;

use App\DTOs\Products\ProductDTO;
use App\Enums\Products\ProductStatus;
use App\Interfaces\Products\DataExtractorInterface;
use Illuminate\Support\LazyCollection;
use SplFileObject;

class CsvStrategy implements DataExtractorInterface
{
    public function __construct(private string $filePath) {}

    public function extract(): LazyCollection
    {
        return LazyCollection::make(function () {
            $file = new SplFileObject($this->filePath);
            $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
            $file->setCsvControl(',');

            $header = null;

            foreach ($file as $row) {
                if (!$row || count($row) < 2) continue;

                // remove trailing empty columns sometimes produced by CSV readers
                $row = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row);

                if ($header === null) {
                    $header = $row;
                    continue;
                }

                $assoc = @array_combine($header, $row);
                if (!is_array($assoc)) continue;

                yield $this->mapRowToDTO($assoc);
            }
        });
    }

    private function mapRowToDTO(array $data): ProductDTO
    {
        $variationsRaw = $data['variations'] ?? '[]';
        $variations = is_string($variationsRaw) ? json_decode($variationsRaw, true) : $variationsRaw;
        if (!is_array($variations)) $variations = [];

        return new ProductDTO(
            sku: (string)($data['sku'] ?? ''),
            name: (string)($data['name'] ?? ''),
            status: isset($data['status']) && $data['status'] !== '' ? (string)$data['status'] : null,
            price: isset($data['price']) && $data['price'] !== '' ? (float)$data['price'] : null,
            currency: isset($data['currency']) && $data['currency'] !== '' ? (string)$data['currency'] : null,
            variations: $variations,
            quantity: isset($data['quantity']) && $data['quantity'] !== '' ? (int)$data['quantity'] : null,
        );
    }
}
