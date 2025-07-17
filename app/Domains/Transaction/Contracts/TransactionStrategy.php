<?php

namespace App\Domains\Transaction\Contracts;

use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Validators\TransactionTypeValidator;

interface TransactionStrategy
{
    public function getType(): string;
    public function getValidator(): TransactionTypeValidator;
    public function process(array $data): Transaction;
    public function validateTransaction(array $data): bool;
    public function createOriginatingTransaction(array $data): mixed;
    public function getOriginatingTransactions(int $id): mixed;
}
