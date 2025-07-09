<?php

namespace App\Domains\PaymentMethod\Factories;

use App\Domains\PaymentMethod\Contracts\PaymentMethodStrategy;

abstract class AbstractPaymentMethodFactory
{
    abstract public function createPaymentMethod(string $type): PaymentMethodStrategy;

    abstract public function getSupportedTypes(): array;
}
