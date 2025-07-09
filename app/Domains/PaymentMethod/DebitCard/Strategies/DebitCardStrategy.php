<?php

namespace App\Domains\PaymentMethod\DebitCard\Strategies;

use App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy;
use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;

class DebitCardStrategy implements PaymentMethodStrategy
{
    private DebitCardService $debitCardService;

    public function __construct(DebitCardService $debitCardService)
    {
        $this->debitCardService = $debitCardService;
    }

    public function create(array $data): DebitCard|bool
    {
        try {
            $card = $this->debitCardService->create($data);
            return $card;
        } catch (\Exception $e) {
            abort(422, 'Cannot save card');
        }
    }
}
