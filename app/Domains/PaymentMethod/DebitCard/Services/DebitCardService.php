<?php

namespace App\Domains\PaymentMethod\DebitCard\Services;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\PaymentMethod\DebitCard\Repositories\DebitCardRepository;
use App\Domains\Vault\Models\Vault;

class DebitCardService
{
    protected DebitCardRepository $repository;

    private Vault $vault;

    public function __construct(DebitCardRepository $repository, Vault $vault)
    {
        $this->repository = $repository;
        $this->vault = $vault;
    }

    public function create(array $data): DebitCard
    {

        $vaultData = [
            'card_number' => $data['card_number'],
            'card_expiry' => $data['expiry_month'] . '/' . $data['expiry_year'],
            'card_cvv' => $data['cvv'],
        ];

        $vaultCard = $this->vault->create(['details' => encrypt(json_encode($vaultData))]);

        $debitCard = [
            'issuer' => $data['issuer'],
            'vault_id' => $vaultCard->id,
            'user_id' => $data['user_id'],
            'display_name' => $data['display_name'],
        ];

        return $this->repository->create($debitCard);
    }

    public function getUserDebitCards(int $userId): mixed
    {
        return $this->repository->findByUserId($userId);
    }

    public function findById(int $id): DebitCard
    {
        return $this->repository->findById($id);
    }
}
