<?php

namespace App\Domains\Transaction\Contracts;

use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Validators\TransactionTypeValidator;

interface TransactionStrategy
{
    public function getType(): string;
    public function getValidator(): TransactionTypeValidator;

    /**
     * 1. Start the transaction (e.g., create scheduled payment, tracking ref, etc.)
     */
    public function startTransaction(array $data): mixed;

    /**
     * 2. Acknowledge the transaction (e.g., bank confirms, update transaction & tracking)
     */
    public function acknowledgeTransaction(array $data): mixed;

    /**
     * 3. Finalise the transaction (e.g., mark scheduled payment as paid, update balances)
     */
    public function finaliseTransaction(array $data): mixed;

    /**
     * Get all context transactions for this type.
     * If $contextId is provided, filter by context (e.g., user, card, loan).
     * If null, return all context transactions for this type.
     */
    public function getContextTransactions(int|string $contextId = null): mixed;
}
