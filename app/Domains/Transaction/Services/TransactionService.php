<?php
namespace App\Domains\Transaction\Services;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Models\TransactionTracking;
use Illuminate\Support\Facades\DB;
class TransactionService
{
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
        $tracking = TransactionTracking::where('reference', $reference)->first();

        return Transaction::where('id', $tracking->transaction_id)->first();

    }
    /**
     * Verify transaction exists and matches expected amount
     */
    public function verifyTransaction(string $reference, float $expectedAmount): bool
    {
        $tracking = TransactionTracking::where('reference', $reference)->first();

//        dd($tracking->transaction);
        if (!$tracking || !$tracking->transaction) {
            return false;
        }
        return (float)$tracking->transaction->amount == $expectedAmount;
    }
}
