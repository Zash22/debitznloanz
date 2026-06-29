<?php

namespace App\Domains\Transaction\TransactionTypes;

use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\Models\DebitCardTransaction;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Transaction\Validators\DebitCardTransactionValidator;
use App\Domains\User\Models\User;

class DebitCardTransactionType implements TransactionStrategy
{
    private const TYPE = 'debit_card';
    private TransactionService $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getValidator(): DebitCardTransactionValidator
    {
        return new DebitCardTransactionValidator();
    }

    /**
     * 1. Start the transaction (create the debit card transaction intent/record)
     */
    public function startTransaction(array $data): mixed
    {
        // Create the DebitCardTransaction intent/record (without confirmed Transaction)
        return DebitCardTransaction::create($data);
    }

    /**
     * 2. Acknowledge the transaction (bank confirms, create Transaction and link)
     */
    public function acknowledgeTransaction(array $data): mixed
    {
        // Create the actual Transaction and link it to the DebitCardTransaction
        $transaction = $this->transactionService->createTransaction([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'ref' => $data['payment_reference'],
            'paid_at' => now()
        ]);
        $debitCardTransaction = DebitCardTransaction::where('payment_reference', $data['payment_reference'])->first();
        if ($debitCardTransaction) {
            $debitCardTransaction->transaction_id = $transaction->id;
            $debitCardTransaction->save();
        }
        return $transaction;
    }

    /**
     * 3. Finalise the transaction (mark as settled/complete if needed)
     */
    public function finaliseTransaction(array $data): mixed
    {
        // Example: mark the DebitCardTransaction as settled/complete
        $debitCardTransaction = DebitCardTransaction::where('payment_reference', $data['payment_reference'])->first();
        if ($debitCardTransaction) {
            $debitCardTransaction->status = 'settled'; // You may need to add a status column if not present
            $debitCardTransaction->save();
        }
        return $debitCardTransaction;
    }
    public function getContextTransactions(int|string $contextId = null): mixed
    {
        $user = User::find($contextId);
        return $user->debit_card_transactions;
    }
}
