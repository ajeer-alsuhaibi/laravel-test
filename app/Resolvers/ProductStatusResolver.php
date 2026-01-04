<?php

namespace App\Resolvers;

use App\Enums\Products\ProductStatus;
use Illuminate\Support\Facades\DB;

class ProductStatusResolver
{
    public function resolve(?string $status): ?string
    {
        if ($status === null || trim($status) === '') {
            return null;
        }

        $status = strtolower(trim($status));

        DB::table('product_statuses')->updateOrInsert(
            ['code' => $status],
            ['updated_at' => now(), 'created_at' => now()]
        );

        return $status;
    }

    public function isDeleted(?string $status): bool
    {
        return strtolower(trim((string)$status)) === ProductStatus::Deleted->value;
    }
}
