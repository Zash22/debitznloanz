<?php

namespace App\Domains\Transaction\Factories;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\Transaction\Models\DebitCardTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebitCardTransactionFactory extends Factory
{
    protected $model = DebitCardTransaction::class;

    public function definition(): array
    {
        return [
            'debit_card_id'  => DebitCard::factory(),
            'amount'   => $this->faker->randomFloat(2, 10, 10000),
            'payment_reference' => $this->faker->uuid,
        ];
    }
}
