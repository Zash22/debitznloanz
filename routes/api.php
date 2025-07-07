<?php

use App\Domains\PaymentMethod\DebitCard\Controllers\DebitCardController;
use Illuminate\Support\Facades\Route;

Route::post('/debit-cards', [DebitCardController::class, 'store']);

