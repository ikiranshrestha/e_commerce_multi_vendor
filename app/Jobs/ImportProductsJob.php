<?php

namespace App\Jobs;

use App\Models\Import;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Import $import;

    public function __construct(Import $import)
    {
        $this->import = $import;
    }

    public function handle(): void
    {
        // Resolve the repository here
        $productRepo = App::make(ProductRepositoryInterface::class);

        $this->import->update(['status' => 'processing']);

        $filePath = storage_path('app/' . $this->import->file_path);
        $merchant = $this->import->merchant;

        if (!file_exists($filePath)) {
            $this->import->update(['status' => 'failed']);
            Log::error("CSV file not found: {$filePath}");
            return;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->import->update(['status' => 'failed']);
            Log::error("Failed to open CSV: {$filePath}");
            return;
        }

        $header = null;

        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }

                try {
                    DB::transaction(function () use ($row, $header, $merchant, $productRepo) {
                        $data = array_combine($header, $row);

                        $productRepo->updateOrCreate(
                            ['merchant_id' => $merchant->id, 'sku' => $data['sku']],
                            [
                                'name' => $data['name'],
                                'description' => $data['description'] ?? null,
                                'price' => $data['price'],
                                'stock' => $data['stock'] ?? 0,
                            ]
                        );
                    });
                } catch (\Throwable $e) {
                    Log::warning('Skipped invalid CSV row', [
                        'merchant_id' => $merchant->id,
                        'row' => $row,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->import->update(['status' => 'completed']);
        } catch (\Throwable $e) {
            $this->import->update(['status' => 'failed']);
            Log::error('CSV import failed', [
                'merchant_id' => $merchant->id,
                'error' => $e->getMessage(),
            ]);
        } finally {
            fclose($handle);
        }
    }
}
