<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('sellers', SellerController::class);
    Route::get('sellers/{seller}/sales', [SellerController::class, 'salesBySeller']);
    Route::post('sellers/{seller}/resend-email', [SellerController::class, 'resendDailyEmail']);
    Route::apiResource('sales', SaleController::class);
});

