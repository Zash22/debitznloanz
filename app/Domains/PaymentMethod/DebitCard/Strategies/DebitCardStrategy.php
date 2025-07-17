<?php

namespace App\Domains\PaymentMethod\DebitCard\Strategies;

use App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy;
use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;

class DebitCardStrategy implements PaymentMethodStrategy
{
    private DebitCardService $debitCardService;
    private const TYPE = 'debit_card';

    public function __construct(DebitCardService $debitCardService)
    {
        $this->debitCardService = $debitCardService;
    }

    public function create(array $data): DebitCard|bool
    {
        try {
            return $this->debitCardService->create($data);
        } catch (\Exception $e) {
            abort(422, 'Cannot save card');
        }
    }

    public function validate(array $data): bool
    {
        return isset($data['card_number']) &&
            isset($data['expiry_month']) &&
            isset($data['expiry_year']) &&
            isset($data['cvv']);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getDetails(): array
    {
        return [
            'type' => self::TYPE,
            'name' => 'Debit Card',
            'fields' => [
                'card_number',
                'expiry_month',
                'expiry_year',
                'cvv'
            ]
        ];
    }
}
