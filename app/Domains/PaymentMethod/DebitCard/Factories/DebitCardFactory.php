<?php

namespace App\Domains\PaymentMethod\DebitCard\Factories;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use App\Domains\Vault\Models\Vault;
use Illuminate\Database\Eloquent\Factories\Factory;

class DebitCardFactory extends Factory
{
    protected $model = DebitCard::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'display_name' => $this->faker->words(3, true),
            'issuer' => $this->faker->company(),
            'vault_id' => Vault::factory(),
        ];
    }
}
