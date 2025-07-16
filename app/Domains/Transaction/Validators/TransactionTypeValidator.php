<?php
namespace App\Domains\Transaction\Validators;

interface TransactionTypeValidator
{
    public function rules(): array;

    public function messages(): array;

    public function validate(array $data): void;
}
