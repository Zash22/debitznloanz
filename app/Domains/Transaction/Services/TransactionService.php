<?php

namespace App\Domains\Transaction\Services;

use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\Factories\TransactionTypeFactory;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Models\TransactionTracking;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        protected TransactionTypeFactory $factory
    ) {
    }

    /**
     * Create a new transaction with tracking
     */
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            // Create tracking record first
            $tracking = TransactionTracking::create([
                'reference' => $data['ref'] ?? null,
                'transaction_id' => null
            ]);
            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $data['user_id'],
                'amount' => $data['amount'],
                'ref' => $data['ref'] ?? $tracking->id,
                'paid_at' => $data['paid_at'] ?? now()
            ]);
            // Update tracking with transaction ID
            $tracking->update(['transaction_id' => $transaction->id]);
            return $transaction;
        });
    }

    /**
     * Create a new transaction with tracking
     */
    public function insertTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {

            return Transaction::create([...$data]);

        });
    }

    /**
     * Get transaction by reference
     */
    public function getTransactionByReference(string $reference): Transaction|null
    {
        $tracking = TransactionTracking::where('transaction_reference', $reference)->first();
        return Transaction::where('id', $tracking->transaction_id)->first();
    }
    /**
     * Verify transaction exists and matches expected amount
     */
    public function verifyTransaction(string $reference, float $expectedAmount): bool
    {
        $tracking = TransactionTracking::where('transaction_reference', $reference)->first();

        if (!$tracking || !$tracking->transaction) {
            return false;
        }
        return (float)$tracking->transaction->amount == $expectedAmount;
    }

    /**
     * Start the transaction (delegates to strategy)
     */
    public function startTransaction(string $type, array $data)
    {
        $strategy = $this->factory->make($type, $data);
        return $strategy->startTransaction($data);
    }

    /**
     * Acknowledge the transaction (default: TransactionService logic, unless strategy overrides)
     */
    public function acknowledgeTransaction(string $type, array $data)
    {
        $transaction = $this->insertTransaction($data);
        $strategy = $this->factory->make($type, $data);
         $strategy->acknowledgeTransaction($data);
         return $transaction;
    }

    /**
     * Finalise the transaction (delegates to strategy)
     */
    public function finaliseTransaction(string $type, array $data)
    {
        $strategy = $this->factory->make($type, $data);
        return $strategy->finaliseTransaction($data);
    }

    public function getContextTransactions(string $type, int $contextId): mixed
    {
        $strategy = $this->factory->make($type, [$contextId]);
        return $strategy->getContextTransactions($contextId);
    }
}
