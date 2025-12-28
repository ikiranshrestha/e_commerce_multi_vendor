<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('merchants/{merchant}/products/import', [ProductController::class, 'import']);
Route::get('imports/{import}/status', [ProductController::class, 'importStatus']);


Route::middleware(['resolve.merchant'])
    ->prefix('merchants')
    ->group(function () {
        Route::get('collections', [CollectionController::class, 'index']);
        Route::post('collections', [CollectionController::class, 'store']);
        Route::put('collections/{collection}', [CollectionController::class, 'update']);
        Route::delete('collections/{collection}', [CollectionController::class, 'destroy']);
        Route::post('collections/{collection}/products', [CollectionController::class, 'attachProducts']);
        Route::delete('collections/{collection}/products', [CollectionController::class, 'detachProducts']);
    });

// Route::prefix('merchants/{merchant}')->group(function () {
//     Route::get('collections', [CollectionController::class, 'index']); // List all collections for merchant
//     Route::post('collections', [CollectionController::class, 'store']); // Create collection + attach products
// });

// Route::put('collections/{collection}', [CollectionController::class, 'update']); // Update collection + sync products
// Route::delete('collections/{collection}', [CollectionController::class, 'destroy']); // Delete collection
// Route::post('collections/{collection}/products', [CollectionController::class, 'attachProducts']); // Attach products
// Route::delete('collections/{collection}/products', [CollectionController::class, 'detachProducts']); // Detach products
