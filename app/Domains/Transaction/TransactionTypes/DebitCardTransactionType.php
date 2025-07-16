<?php
namespace App\Domains\Transaction\TransactionTypes;
use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\PaymentMethod\DebitCard\Models\DebitCardTransaction;
use App\Domains\Transaction\Validators\DebitCardTransactionValidator;

class DebitCardTransactionType implements TransactionStrategy
{
    private const TYPE = 'debit_card';
    private TransactionService $transactionService;
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    public function process(array $data): Transaction
    {
        $transaction = $this->transactionService->createTransaction([
            'user_id' => $data['user_id'],
            'amount' => $data['amount'],
            'ref' => $data['payment_reference'],
            'paid_at' => now()
        ]);
        DebitCardTransaction::create([
            'debit_card_id' => $data['debit_card_id'],
            'amount' => $data['amount'],
            'payment_reference' => $data['payment_reference'],
            'transaction_id' => $transaction->id
        ]);
        return $transaction;
    }
    public function validateTransaction(array $data): bool
    {
        return isset($data['debit_card_id']) &&
            isset($data['amount']) &&
            isset($data['payment_reference']) &&
            isset($data['user_id']);
    }
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getValidator(): DebitCardTransactionValidator
    {
        return new DebitCardTransactionValidator();
    }

    public function createOriginatingTransaction(array $array): void
    {

    }
}
