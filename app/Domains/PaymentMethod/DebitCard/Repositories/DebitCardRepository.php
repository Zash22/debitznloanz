<?php

namespace App\Domains\PaymentMethod\DebitCard\Repositories;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;

class DebitCardRepository
{
    public function create(array $data): DebitCard
    {
        return DebitCard::create($data);
    }

    public function findByUserId(int $userId)
    {
        return DebitCard::where('user_id', $userId)->get();
    }

}
