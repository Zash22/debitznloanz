<?php
namespace App\Domains\Transaction\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DebitCardTransactionValidator implements TransactionTypeValidator
{
    public function rules(): array
    {
        return [
            'debit_card_id' => ['required', 'string', 'digits_between:12,19'],
            'amount' => ['required', 'integer', 'between:1,12'],
            'payment_reference' => ['required', 'integer', 'between:1,12'],
        ];
    }

    public function messages(): array
    {
        return [
            'debit_card_id.required' => 'A debit card id is required',

            'payment_reference.required' => 'A payment reference is required',
            'payment_reference.string' => 'The payment reference must be a string',

            'amount.required' => 'Payment amount is required.',
            'amount.float' => 'Payment amount must be a float value',
        ];
    }

    public function validate(array $data): void
    {
        Validator::make($data, $this->rules(), $this->messages())->validate();
    }
}
