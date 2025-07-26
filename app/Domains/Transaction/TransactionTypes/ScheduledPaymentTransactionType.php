<?php

namespace App\Domains\Transaction\TransactionTypes;

use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Transaction\Validators\TransactionTypeValidator;

class ScheduledPaymentTransactionType implements TransactionStrategy
{
    private const TYPE = 'scheduled_payment';
    private TransactionService $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function process(array $data): Transaction
    {
        $scheduledPayment = ScheduledPayment::findOrFail($data['scheduled_payment_id']);

        $transaction = $this->transactionService->createTransaction([
            'user_id' => $data['user_id'],
            'amount' => $scheduledPayment->amount,
            'ref' => 'scheduled_payment_' . $scheduledPayment->id,
            'paid_at' => now()
        ]);
        $scheduledPayment->update([
            'paid' => true,
            'paid_at' => now(),
            'transaction_id' => $transaction->id
        ]);
        return $transaction;
    }
    public function validateTransaction(array $data): bool
    {
//        return;
        return isset($data['scheduled_payment_id']) &&
            isset($data['user_id']);
    }
    public function getType(): string
    {
        return self::TYPE;
    }

    public function store(array $scheduledPayments): void
    {

        foreach ($scheduledPayments['payments'] as $payment) {
            ScheduledPayment::create([
                'loan_id' => $scheduledPayments['loan_id'],
                'amount' => $payment['amount'],
                'run_date' => $payment['run_date'],
            ]);
        }
    }

    public function createOriginatingTransaction(array $scheduledPayments): mixed
    {
        foreach ($scheduledPayments['payments'] as $payment) {
            ScheduledPayment::create([
                'loan_id' => $scheduledPayments['loan_id'],
                'amount' => $payment['amount'],
                'run_date' => $payment['run_date'],
            ]);
        }
        return true;
    }

    public function getValidator(): TransactionTypeValidator
    {
        // TODO: Implement getValidator() method.
    }

    public function getOriginatingTransactions(int $id): mixed
    {
        // TODO: Implement getOriginatingTransactions() method.
    }
}
