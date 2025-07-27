<?php

namespace App\Domains\Transaction\Controllers;

use App\Domains\Transaction\Factories\TransactionTypeFactory;
use App\Domains\Transaction\Requests\CreateDebitCardTransactionRequest;
use App\Domains\Transaction\Resources\DebitCardTransactionResource;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Transaction\TransactionTypes\DebitCardTransactionType;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DebitCardTransactionController extends Controller
{
    use AuthorizesRequests;

    private TransactionService $transactionService;

    public function __construct(
        TransactionService $transactionService,
        DebitCardTransactionType $debitCardTransactionType
    ) {
        $this->transactionService = new TransactionService(
            new TransactionTypeFactory(null, null, $debitCardTransactionType)
        );
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return DebitCardTransactionResource::collection(
            $this->transactionService->getContextTransactions('debit_card', $request->user()->id)
        );
    }


    public function store(CreateDebitCardTransactionRequest $request): DebitCardTransactionResource
    {
        $validated = $request->validated();

        $transaction = $this->transactionService->startTransaction(
            'debit_card',
            $validated
        );

        return new DebitCardTransactionResource($transaction);
    }
}
