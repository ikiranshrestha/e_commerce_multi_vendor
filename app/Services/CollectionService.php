<?php

namespace App\Services;

use App\Models\Merchant;
use App\Repositories\Contracts\CollectionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;

class CollectionService extends BaseService
{
    protected CollectionRepositoryInterface $collectionRepository;

    public function __construct(CollectionRepositoryInterface $collectionRepository)
    {
        parent::__construct($collectionRepository);
        $this->collectionRepository = $collectionRepository;
    }

    public function list(Merchant $merchant)
    {
        return $this->collectionRepository->forMerchant($merchant->id);
    }

    public function createWithProducts(Merchant $merchant, array $data)
    {
        DB::beginTransaction();
        try {
            $data['merchant_id'] = $merchant->id;
            $collection = $this->create($data);

            if (!empty($data['product_ids'])) {
                // Filter product IDs by merchant ownership
                $productIds = $this->collectionRepository->filterMerchantProducts($merchant->id, $data['product_ids']);
                $this->collectionRepository->attachProducts($collection->id, $productIds);
            }

            DB::commit();
            return $collection->load('products');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithProducts(Merchant $merchant, int $collectionId, array $data)
    {
        DB::beginTransaction();
        try {
            $collection = $this->update($collectionId, [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            if (array_key_exists('product_ids', $data)) {
                $productIds = $this->collectionRepository->filterMerchantProducts($merchant->id, $data['product_ids']);
                $this->collectionRepository->syncProducts($collection->id, $productIds);
            }

            DB::commit();
            return $collection->load('products');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteForMerchant(Merchant $merchant, int $collectionId): bool
    {
        $collection = $this->collectionRepository->find($collectionId);

        if ($collection->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized');
        }

        return $this->delete($collectionId); // calls BaseService delete
    }


    public function attachProducts(Merchant $merchant, int $collectionId, array $productIds)
    {
        $collection = $this->collectionRepository->find($collectionId);

        if ($collection->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized');
        }

        $productIds = $this->collectionRepository->filterMerchantProducts($merchant->id, $productIds);
        $this->collectionRepository->attachProducts($collectionId, $productIds);

        return $collection->load('products');
    }

    public function detachProducts(Merchant $merchant, int $collectionId, array $productIds = [])
    {
        $collection = $this->collectionRepository->find($collectionId);

        if ($collection->merchant_id !== $merchant->id) {
            abort(403, 'Unauthorized');
        }

        $productIds = $this->collectionRepository->filterMerchantProducts($merchant->id, $productIds);
        $this->collectionRepository->detachProducts($collectionId, $productIds);

        return $collection->load('products');
    }
}
