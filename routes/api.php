<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ServiceController;

// ✅ Route publik (tidak perlu login)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// ✅ Route yang membutuhkan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    // Data user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Service
    Route::get('/service', [ServiceController::class, 'getService'])->name('service');
    Route::post('/service/store', [ServiceController::class, 'StoreOrUpdateService'])->name('service.store');

    // Deposit
    Route::post('/deposit', [DepositController::class, 'deposit'])->name('deposit');

    // history
    Route::post('/history/deposit',[HistoryController::class,'getHistoryDepo'])->name('history.deposit');
});
