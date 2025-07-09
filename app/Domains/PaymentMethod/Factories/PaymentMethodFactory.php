<?php

namespace App\Domains\PaymentMethod\Factories;

use App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy;
use App\Domains\PaymentMethod\DebitCard\Strategies\DebitCardStrategy;
use InvalidArgumentException;

class PaymentMethodFactory extends AbstractPaymentMethodFactory
{
    private array $strategies;

    public function __construct(DebitCardStrategy $debitCardStrategy)
    {
        $this->strategies = [
            'debit_card' => $debitCardStrategy
        ];
    }

    public function createPaymentMethod(string $type): PaymentMethodStrategy
    {
        if (!isset($this->strategies[$type])) {
            throw new InvalidArgumentException(
                sprintf('Payment method type "%s" not supported', $type)
            );
        }
        return $this->strategies[$type];
    }

    public function getSupportedTypes(): array
    {
        return array_keys($this->strategies);
    }
}
