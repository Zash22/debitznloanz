<?php

namespace App\Domains\Transaction\Validators;

class ScheduledPaymentTransactionValidator extends TransactionTypeValidator
{
    public function validate(array $data): bool
    {
        return isset($data['scheduled_payment_id'], $data['user_id']);
    }
}
