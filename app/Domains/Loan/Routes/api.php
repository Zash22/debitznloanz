<?php

use App\Domains\Loan\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.ratelimit', 'auth:sanctum'])->group(function () {
    Route::post('/loans', [LoanController::class, 'store']);
});
