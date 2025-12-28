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
        $productRepo = App::make(ProductRepositoryInterface::class);

        $this->import->update(['status' => 'processing']);

        $filePath = storage_path('app/' . $this->import->file_path);

        if (!file_exists($filePath)) {
            $this->failImport('CSV file not found');
            return;
        }

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            $this->failImport('Failed to open CSV');
            return;
        }

        try {
            // Calculate total rows
            $totalRows = 0;
            while (fgetcsv($handle, 1000, ',')) {
                $totalRows++;
            }

            $this->import->update(['total_rows' => max(0, $totalRows - 1)]);
            rewind($handle);

            $header = null;
            $merchant = $this->import->merchant;

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

                    $this->import->increment('processed_rows');
                } catch (\Throwable $e) {
                    $this->import->increment('failed_rows');

                    Log::warning('Skipped invalid CSV row', [
                        'import_id' => $this->import->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            $this->import->update(['status' => 'completed']);
            SendImportCompletedEmail::dispatch($this->import)
                ->onQueue('emails');
        } catch (\Throwable $e) {
            $this->failImport($e->getMessage());
        } finally {
            fclose($handle);
        }
    }

    protected function failImport(string $message): void
    {
        $this->import->update([
            'status' => 'failed',
            'error_message' => $message,
        ]);

        SendImportCompletedEmail::dispatch($this->import)
            ->onQueue('emails');


        Log::error('Import failed', [
            'import_id' => $this->import->id,
            'message' => $message,
        ]);
    }
}
