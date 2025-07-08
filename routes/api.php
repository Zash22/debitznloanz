<?php

use App\Domains\PaymentMethod\DebitCard\Controllers\DebitCardController;
use Illuminate\Support\Facades\Route;

Route::post('/debit-cards', [DebitCardController::class, 'store']);
    Route::middleware('can:viewAny,'.DebitCard::class)->get('/debit-cards', [DebitCardController::class, 'index']);
    Route::middleware('can:create,'.DebitCard::class)->post('/debit-cards', [DebitCardController::class, 'store']);

