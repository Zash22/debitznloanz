<?php

namespace App\Domains\Transaction\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\User\Models\User;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'amount'   => $this->faker->randomFloat(2, 10, 10000),
            'transaction_ref'      => $this->faker->uuid,
            'paid_at'  => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
