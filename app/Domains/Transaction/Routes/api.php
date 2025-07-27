<?php

use App\Domains\Transaction\Controllers\DebitCardTransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.ratelimit', 'auth:sanctum'])->group(function () {
    Route::post('/debit-card-transactions', [DebitCardTransactionController::class, 'store']);
    Route::get('/debit-card-transactions', [DebitCardTransactionController::class, 'index']);
});
