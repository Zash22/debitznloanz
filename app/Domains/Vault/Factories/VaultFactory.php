<?php

namespace App\Domains\Vault\Factories;

use App\Domains\Vault\Models\Vault;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;

class VaultFactory extends Factory
{
    protected $model = Vault::class;
    public function definition(): array
    {
        $vaultData = [
            'card_number' => $this->faker->creditCardNumber(),
            'card_expiry' => $this->faker->creditCardExpirationDate()->format('m/Y'),
            'card_cvv' => $this->faker->numberBetween(100, 999),
        ];
        return [
            'details' => encrypt(json_encode($vaultData)),
        ];
    }
}
