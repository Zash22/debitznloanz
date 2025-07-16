<?php

namespace App\Domains\Transaction\Controllers;

use App\Domains\Transaction\Requests\CreateDebitCardTransactionRequest;
use App\Domains\Transaction\Services\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DebitCardTransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(
        TransactionService $transactionService
    ) {
        $this->transactionService = $transactionService;

    }

    public function store(CreateDebitCardTransactionRequest $request): JsonResponse
    {


        $validated = $request->validated();

        $result = $this->transactionService->createOriginatingTransaction(
            'debit_card',
            $validated
        );

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }
}

