<?php

namespace App\Repositories\Contracts;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

interface CollectionRepositoryInterface extends BaseRepositoryInterface
{
    public function forMerchant(int $merchantId): EloquentCollection;

    public function attachProducts(int $collectionId, array $productIds): void;

    public function syncProducts(int $collectionId, array $productIds): void;

    public function detachProducts(int $collectionId, array $productIds = []): void;

    public function filterMerchantProducts(int $merchantId, array $productIds): array;
}
