<?php

use App\Http\Controllers\AirportController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommissionRuleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::prefix('auth')->group(function () {
//     // Auth
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/login', [AuthController::class, 'login']);

//     Route::middleware('auth:sanctum')->group(function () {
//         Route::post('/logout', [AuthController::class, 'logout']);
//         Route::get('/me', [AuthController::class, 'me']);
//     });
// });

Route::apiResource('commission-rules', CommissionRuleController::class);
Route::apiResource('airports', AirportController::class)->only(['index']);




// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
