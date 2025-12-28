<?php

namespace App\Services;

use App\Jobs\ImportProductsJob;
use App\Models\Merchant;
use App\Repositories\Contracts\ImportRepositoryInterface;
use Illuminate\Http\UploadedFile;

class ProductService
{
    public function __construct(protected ImportRepositoryInterface $importRepository) {}

    public function import(Merchant $merchant, UploadedFile $file)
    {
        $path = $file->store('imports');

        $import = $this->importRepository->create([
            'merchant_id' => $merchant->id,
            'file_path'   => $path,
            'status'      => 'pending',
        ]);

        ImportProductsJob::dispatch($import);

        return $import;
    }
}
