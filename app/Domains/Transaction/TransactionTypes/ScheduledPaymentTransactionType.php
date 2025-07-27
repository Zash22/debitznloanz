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
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getValidator(): TransactionTypeValidator
    {
        // You should return a real validator here
        return new TransactionTypeValidator();
    }

    /**
     * 1. Start the transaction (create scheduled payment(s))
     */
    public function startTransaction(array $scheduledPayments): mixed
    {
        $created = [];
        foreach ($scheduledPayments['payments'] as $payment) {
            $created[] = ScheduledPayment::create([
                'loan_id' => $scheduledPayments['loan_id'],
                'amount' => $payment['amount'],
                'run_date' => $payment['run_date'],
            ]);
        }
        return $created;
    }

    /**
     * 3. Finalise the transaction (mark scheduled payment as paid)
     */
    public function finaliseTransaction(array $data): mixed
    {
        $scheduledPayment = ScheduledPayment::findOrFail($data['scheduled_payment_id']);
        $scheduledPayment->paid = true;
        $scheduledPayment->paid_at = now();
        $scheduledPayment->save();
        return $scheduledPayment;
    }

    /**
     * 2. Acknowledge the transaction (e.g., bank confirms, update transaction & tracking)
     */
    public function acknowledgeTransaction(array $data): mixed
    {
        // TODO: Implement acknowledgeTransaction() method.
    }

    /**
     * Get all context transactions for this type.
     * If $contextId is provided, filter by context (e.g., user, card, loan).
     * If null, return all context transactions for this type.
     */
    public function getContextTransactions(int|string $contextId = null): mixed
    {
        // TODO: Implement getContextTransactions() method.
    }
}
