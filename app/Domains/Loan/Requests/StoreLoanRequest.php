<?php

namespace App\Domains\Loan\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'term' => ['required', 'integer', 'min:1'],
            'frequency' => ['required', 'in:monthly,biweekly'],
            'term_amount' => ['required', 'numeric', 'min:0.01'],
            'principal_amount' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
