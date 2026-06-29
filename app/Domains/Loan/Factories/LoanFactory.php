<?php

namespace App\Domains\Loan\Factories;

use App\Domains\Loan\Models\Loan;
use App\Domains\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Loan>
 */
class LoanFactory extends Factory
{
    protected $model = Loan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'term' => 6,
            'frequency' => 'monthly',
            'term_amount' => 1000.00,
            'principal_amount' => 6000.00,
            'remaining_balance' => 6000.00,
        ];
    }
}
