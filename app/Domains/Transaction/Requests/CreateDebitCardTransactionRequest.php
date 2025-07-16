<?php

namespace App\Domains\Transaction\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDebitCardTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Already protected by middleware
    }

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
}
