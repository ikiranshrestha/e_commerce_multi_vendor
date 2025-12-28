<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Services\CollectionService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    protected CollectionService $service;

    public function __construct(CollectionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $merchant = $request->attributes->get('merchant');

        return ApiResponse::success(
            $this->service->list($merchant)
        );
    }

    public function store(Request $request)
    {
        $merchant = $request->attributes->get('merchant');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $collection = $this->service->createWithProducts($merchant, $validated);

        return ApiResponse::success(
            $collection,
            'Collection created',
            201
        );
    }

    public function update(Request $request, Collection $collection)
    {
        $merchant = $request->attributes->get('merchant');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $collection = $this->service->updateWithProducts($merchant, $collection->id, $validated);

        return ApiResponse::success(
            $collection,
            'Collection updated'
        );
    }

    public function destroy(Request $request, Collection $collection)
    {
        $merchant = $request->attributes->get('merchant');

        $this->service->deleteForMerchant($merchant, $collection->id);

        return ApiResponse::success(
            null,
            'Collection deleted'
        );
    }

    public function attachProducts(Request $request, Collection $collection)
    {
        $merchant = $request->attributes->get('merchant');

        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $collection = $this->service->attachProducts($merchant, $collection->id, $validated['product_ids']);

        return ApiResponse::success(
            $collection,
            'Products attached'
        );
    }

    public function detachProducts(Request $request, Collection $collection)
    {
        $merchant = $request->attributes->get('merchant');

        $validated = $request->validate([
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $collection = $this->service->detachProducts($merchant, $collection->id, $validated['product_ids'] ?? []);

        return ApiResponse::success(
            $collection,
            'Products detached'
        );
    }
}
