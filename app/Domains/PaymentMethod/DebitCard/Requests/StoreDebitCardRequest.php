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
            'user_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'      => 'The user ID is required.',
            'user_id.exists'        => 'The selected user ID is invalid.',

            'card_number.required'      => 'A card number is required.',
            'card_number.digits_between' => 'The card number must be between 13 and 19 digits.',

            'expiry_month.required' => 'The expiry month is required.',
            'expiry_month.integer'  => 'The expiry month must be a valid number.',
            'expiry_month.between'  => 'The expiry month must be between 1 and 12.',

            'expiry_year.required' => 'The expiry year is required.',
            'expiry_year.integer'  => 'The expiry year must be a valid number.',
            'expiry_year.min'      => 'The expiry year must not be in the past.',

            'cvv.required'          => 'The CVV is required.',
            'cvv.digits_between'    => 'The CVV must be 3 or 4 digits.',

            'issuer.required'       => 'The issuer name is required.',
            'issuer.string'         => 'The issuer must be a valid string.',
            'issuer.max'            => 'The issuer may not be greater than 255 characters.',

            'display_name.required' => 'The display name is required.',
            'display_name.string'   => 'The display name must be a valid string.',
            'display_name.max'      => 'The display name may not be greater than 255 characters.',
        ];
    }

}
