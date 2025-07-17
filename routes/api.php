<?php

use App\Domains\PaymentMethod\DebitCard\Controllers\DebitCardController;
use App\Domains\Transaction\Controllers\DebitCardTransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'api.ratelimit'])->group(function () {
    Route::get('/debit-cards', [DebitCardController::class, 'index']);
    Route::get('/debit-cards/{id}', [DebitCardController::class, 'show']);
    Route::post('/debit-cards', [DebitCardController::class, 'store']);
    Route::post('/debit-card-transactions', [DebitCardTransactionController::class, 'store']);
    Route::get('/debit-card-transactions', [DebitCardTransactionController::class, 'index']);
});
