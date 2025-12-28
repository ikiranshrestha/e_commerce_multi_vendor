<?php

namespace App\Repositories;

use App\Models\Collection;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class CollectionRepository extends BaseRepository implements CollectionRepositoryInterface
{

    public function __construct(Collection $model)
    {
        parent::__construct($model);
    }

    public function forMerchant(int $merchantId): EloquentCollection
    {
        return $this->query()
            ->where('merchant_id', $merchantId)
            ->with('products')
            ->get();
    }

    public function attachProducts(int $collectionId, array $productIds): void
    {
        $this->find($collectionId)
            ->products()
            ->attach($productIds);
    }

    public function syncProducts(int $collectionId, array $productIds): void
    {
        $this->find($collectionId)
            ->products()
            ->sync($productIds);
    }

    public function detachProducts(int $collectionId, array $productIds = []): void
    {
        $this->find($collectionId)
            ->products()
            ->detach($productIds);
    }

    public function filterMerchantProducts(int $merchantId, array $productIds): array
    {
        return $this->model->products()
            ->where('merchant_id', $merchantId)
            ->whereIn('id', $productIds)
            ->pluck('id')
            ->toArray();
    }
}
