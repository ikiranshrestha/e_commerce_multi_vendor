<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::post('merchants/{merchant}/products/import', [ProductController::class, 'import']);
Route::get('imports/{import}/status', [ProductController::class, 'importStatus']);

