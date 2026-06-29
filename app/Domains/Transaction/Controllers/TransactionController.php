<?php

namespace App\Domains\Transaction\Controllers;

use App\Domains\Transaction\Requests\StoreTransactionRequest;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Loan\Services\LoanService;
use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Transaction\Resources\TransactionAcknowledgeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Exception;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;
    protected LoanService $loanService;

    public function __construct(TransactionService $transactionService, LoanService $loanService)
    {
        $this->transactionService = $transactionService;
        $this->loanService = $loanService;
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $data = $request->validated();
        $finalise = false;
        try {
//            dd($data);

            preg_match('/^([a-z_]+)_\d+$/', $data['transaction_ref'], $matches);
            $transactionType = $matches[1]; // 'scheduled_payment'

//            $transactionType = $data['transaction_type'];

            $transaction = $this->transactionService->acknowledgeTransaction($transactionType, $data);
//            dd($transaction);

            if (!$transaction) {
                abort(422);

            }else {
                $finalise = true;
            }
            return (new TransactionAcknowledgeResource(null))->response()->setStatusCode(201);

        } catch (Exception $e) {
            $finalise = false;
            return response()->json(['message' => 'bad format'], 422);
        } finally {
            if ($finalise) {
                $this->transactionService->finaliseTransaction($transactionType, $transaction->toArray());
            }
        }
    }
}
