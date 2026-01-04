<?php

namespace App\Console\Commands\Products;

use App\Enums\Products\DataSource;
use App\Services\Products\ProductSyncService;
use Illuminate\Console\Command;

class ImportProductsCommand extends Command
{
    protected $signature = 'import:products
        {source : The source type (csv or api)}
        {--path= : Path to CSV file}
        {--url= : URL for API}';

    protected $description = 'Import products from csv file or API into the database.
Example:
php artisan import:products csv --path=storage/app/products_500k.csv
php artisan import:products api --url=https://example.com/products?page=1&limit=1000
';

    public function __construct(private readonly ProductSyncService $syncService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $sourceEnum = DataSource::tryFrom($this->argument('source'));

        if (!$sourceEnum) {
            $this->error("Invalid source type. Please provide 'csv' or 'api'.");
            return self::FAILURE;
        }

        $path = $this->option('path');   // ✅ option, not argument
        $apiUrl = $this->option('url');  // ✅ option, not argument

        match ($sourceEnum) {
            DataSource::CSV => $path ?: $this->fail('CSV path required via --path'),
            DataSource::API => $apiUrl ?: $this->fail('API URL required via --url'),
        };

        $this->syncService->syncProducts($sourceEnum, $path, $apiUrl);

        $this->info('Products imported and synced successfully.');
        return self::SUCCESS;
    }
}
