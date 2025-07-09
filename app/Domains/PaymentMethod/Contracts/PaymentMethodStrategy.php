<?php

namespace App\Domains\PaymentMethod\Contracts;

interface PaymentMethodStrategy
{
    public function create(array $data): mixed;
}
