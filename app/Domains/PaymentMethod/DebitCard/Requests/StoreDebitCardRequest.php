<?php

namespace App\Domains\PaymentMethod\DebitCard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDebitCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Already protected by middleware
    }

    public function rules(): array
    {
        return [
            'display_name' => ['required', 'string'],
            'card_number' => ['required', 'string', 'digits_between:12,19'],
            'expiry_month' => ['required', 'integer', 'between:1,12'],
            'expiry_year' => ['required', 'integer', 'min:' . date('Y')],
            'cvv' => ['required', 'string', 'size:3'],
            'issuer' => ['required', 'string'],
        ];
    }
}
