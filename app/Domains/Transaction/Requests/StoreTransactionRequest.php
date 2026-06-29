<?php

namespace App\Domains\Transaction\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_ref' => 'required|string',
            'paid_at' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'bad format',
            'user_id.integer' => 'bad format',
            'user_id.exists' => 'bad format',
            'amount.required' => 'bad format',
            'amount.numeric' => 'bad format',
            'amount.min' => 'bad format',
            'transaction_ref.required' => 'bad format',
            'transaction_ref.string' => 'bad format',
            'paid_at.required' => 'bad format',
            'paid_at.date' => 'bad format',
        ];
    }
}
