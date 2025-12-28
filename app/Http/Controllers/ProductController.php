<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Merchant;
use App\Services\ProductService;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(protected ProductService $service) {}

    public function import(Request $request, Merchant $merchant)
    {
        $validated = $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $import = $this->service->import($merchant, $validated['csv_file']);

        return ApiResponse::success(
            $import,
            'CSV import started',
            202
        );
    }

    public function importStatus(Import $import)
    {
        $data = [
            'id' => $import->id,
            'status' => $import->status,
            'processed_rows' => $import->processed_rows,
            'failed_rows' => $import->failed_rows,
            'total_rows' => $import->total_rows ?? null, // if tracked
            'progress' => $import->total_rows
                ? round(($import->processed_rows / $import->total_rows) * 100)
                : null,
        ];

        return ApiResponse::success($data);
    }
}
