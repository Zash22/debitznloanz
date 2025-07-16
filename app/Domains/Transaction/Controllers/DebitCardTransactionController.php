<?php

namespace App\Domains\Transaction\Controllers;

use App\Domains\PaymentMethod\DebitCard\Resources\DebitCardResource;
use App\Domains\Transaction\Factories\TransactionTypeFactory;
use App\Domains\Transaction\Requests\CreateDebitCardTransactionRequest;
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

//    public function index(Request $request): AnonymousResourceCollection
//    {
//        return DebitCardResource::collection(
//            $this->service->getUserDebitCards($request->user()->id)
//        );
//    }


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

