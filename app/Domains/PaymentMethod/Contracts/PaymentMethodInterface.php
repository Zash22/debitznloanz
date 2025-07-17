<?php

namespace App\Domains\PaymentMethod\Contracts;

interface PaymentMethodInterface
{
    public function create(array $data): mixed;

    public function validate(array $data): bool;

    public function getType(): string;
}
