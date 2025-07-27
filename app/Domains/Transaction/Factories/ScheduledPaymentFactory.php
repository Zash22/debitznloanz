<?php

namespace App\Domains\Transaction\Factories;

use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Loan\Models\Loan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledPaymentFactory extends Factory
{
    protected $model = ScheduledPayment::class;

    public function definition()
    {
        return [
            'loan_id' => Loan::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 2000),
            'run_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'paid' => false,
            'paid_at' => null,
            'transaction_id' => null,
        ];
    }
}
