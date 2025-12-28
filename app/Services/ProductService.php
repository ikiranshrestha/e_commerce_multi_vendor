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

        $totalRows = max(0, count(file($file->getRealPath())) - 1);

        $import = $this->importRepository->create([
            'merchant_id' => $merchant->id,
            'file_path'   => $path,
            'status'      => 'pending',
            'total_rows'  => $totalRows,
        ]);

        ImportProductsJob::dispatch($import)
            ->onQueue('import');

        return $import;
    }
}
