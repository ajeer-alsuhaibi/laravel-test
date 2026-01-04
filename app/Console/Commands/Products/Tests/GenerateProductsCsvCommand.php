<?php

namespace App\Console\Commands\Products\Tests;

use Illuminate\Console\Command;

class GenerateProductsCsvCommand extends Command
{
    protected $signature = 'generate:products-csv
        {--rows=500000 : Number of rows to generate}
        {--path=storage/app/products.csv : Output CSV path}
        {--delimiter=, : CSV delimiter}';

    protected $description = 'Generate a large products CSV for import performance testing';

    public function handle(): int
    {
        $rows = (int) $this->option('rows');
        $path = (string) $this->option('path');
        $delimiter = (string) $this->option('delimiter');

        // Ensure directory exists
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $header = ['id', 'name', 'sku', 'price', 'currency', 'variations'];

        $fp = fopen($path, 'w');
        if ($fp === false) {
            $this->error("Cannot open file for writing: {$path}");
            return self::FAILURE;
        }

        // write header
        fputcsv($fp, $header, $delimiter);

        $currencies = ['SAR', 'USD', 'EUR'];
        $colors = ['Red', 'Blue', 'Green', 'Black', 'White'];
        $materials = ['Plastic', 'Metal', 'Wood', 'Cotton', 'Soft'];

        // Lightweight progress bar
        $bar = $this->output->createProgressBar($rows);
        $bar->start();

        for ($i = 1; $i <= $rows; $i++) {
            $name = "Product {$i}";
            $sku = "SKU{$i}";
            $price = random_int(10, 500);
            $currency = $currencies[array_rand($currencies)];

            // Match your CSV "variations" format (array of {name, value})
            $variations = json_encode([
                ['name' => 'Color', 'value' => [$colors[array_rand($colors)]]],
                ['name' => 'Material', 'value' => [$materials[array_rand($materials)]]],
            ], JSON_UNESCAPED_UNICODE);

            fputcsv($fp, [$i, $name, $sku, $price, $currency, $variations], $delimiter);

            //  flush every X rows to be safe on some FS
            if (($i % 5000) === 0) {
                fflush($fp);
            }

            $bar->advance();
        }

        $bar->finish();
        fclose($fp);

        $this->newLine();
        $this->info("✅ Generated {$rows} rows at: {$path}");

        return self::SUCCESS;
    }
}
