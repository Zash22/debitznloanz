<?php

namespace App\Domains\Transaction\Validators;

class DebitCardTransactionValidator extends TransactionTypeValidator
{
    public function validate(array $data): bool
    {
        return isset($data['debit_card_id'], $data['amount'], $data['payment_reference'], $data['user_id']);
    }
}
