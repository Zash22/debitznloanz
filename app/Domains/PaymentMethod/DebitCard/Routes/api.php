<?php

use App\Domains\PaymentMethod\DebitCard\Controllers\DebitCardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.ratelimit', 'auth:sanctum'])->group(function () {
    Route::get('/debit-cards', [DebitCardController::class, 'index']);
    Route::get('/debit-cards/{id}', [DebitCardController::class, 'show']);
    Route::post('/debit-cards', [DebitCardController::class, 'store']);
});
