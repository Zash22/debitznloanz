<?php

namespace App\Domains\Transaction\Validators;

abstract class TransactionTypeValidator
{
    abstract public function validate(array $data): bool;
}
