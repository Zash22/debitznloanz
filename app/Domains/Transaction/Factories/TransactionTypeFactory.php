<?php

namespace App\Domains\Transaction\Factories;

use App\Domains\Transaction\Contracts\TransactionStrategy;
use App\Domains\Transaction\TransactionTypes\DebitCardTransactionType;
use App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType;
use InvalidArgumentException;

class TransactionTypeFactory
{
    private array $strategies = [];
    public function __construct(
        private ?\App\Domains\Transaction\Services\TransactionService $transactionService = null,
        private ?ScheduledPaymentTransactionType $scheduledPaymentTransactionType = null,
        private ?DebitCardTransactionType $debitCardTransactionType = null
    ) {
        if ($scheduledPaymentTransactionType) {
            $this->strategies['scheduled_payment'] = $scheduledPaymentTransactionType;
        }

        if ($debitCardTransactionType) {
            $this->strategies['debit_card_transaction'] = $debitCardTransactionType;
        }
    }

    public function make(string $type, array $data = [], bool $validate = true): TransactionStrategy
    {
        $type = strtolower($type);
        if (!isset($this->strategies[$type])) {
            throw new InvalidArgumentException("Unknown transaction type: {$type}");
        }
        $strategy = $this->strategies[$type];
        if ($validate) {
            $strategy->validateTransaction($data);
        }
        return $strategy;
    }
}
