<?php

namespace App\Domains\PaymentMethod\Contracts;

interface PaymentMethodStrategy extends PaymentMethodInterface
{
    public function getDetails(): array;
}
