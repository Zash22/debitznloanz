<?php

namespace App\Domains\Transaction\Services;

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


    public function processTransaction(string $type, array $data): Transaction
    {
        return $this->transactionContext->processTransaction($type, $data);
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
        $strategy = $this->factory->make($type, $data);
        // Check if the strategy has its own acknowledgeTransaction implementation
        $strategyClass = get_class($strategy);
        $interfaceClass = \App\Domains\Transaction\Contracts\TransactionStrategy::class;
        $strategyMethods = get_class_methods($strategyClass);
        $interfaceMethods = get_class_methods($interfaceClass);
        // If the method is implemented in the concrete class, use it
        if (in_array('acknowledgeTransaction', $strategyMethods) &&
            (!in_array('acknowledgeTransaction', $interfaceMethods) || (new \ReflectionMethod($strategyClass, 'acknowledgeTransaction'))->getDeclaringClass()->getName() !== $interfaceClass)) {
            return $strategy->acknowledgeTransaction($data);
        }
        // Default logic: create Transaction and update tracking
        return $this->createTransaction($data);
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
