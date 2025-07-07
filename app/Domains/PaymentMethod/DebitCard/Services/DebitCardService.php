<?php
namespace App\Domains\PaymentMethod\DebitCard\Services;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\PaymentMethod\DebitCard\Repositories\DebitCardRepository;
use App\Domains\Vault\Models\Vault;
class DebitCardService
{
    /**
     * @var DebitCardRepository
     */
    protected DebitCardRepository $repository;

    /**
     * @var Vault
     */
    private Vault $vault;

    /**
     * @param DebitCardRepository $repository
     */
    public function __construct(DebitCardRepository $repository, Vault $vault)
    {
        $this->repository = $repository;
        $this->vault = $vault;
    }

    /**
     * @param array $data
     * @return DebitCard
     */
    public function create(array $data): DebitCard
    {
//        dd($data);



        $vault_data = [
            'card_number' => $data['card_number'],
            'card_expiry' => $data['expiry_month'] . '/' . $data['expiry_year'],
            'card_cvv' => $data['cvv'],
//            'card_holder' => $data['card_holder'],
        ];

        $vault_card = $this->vault->create(['details' => json_encode($vault_data, true)]);

//        dd($vault_card->id);

        $debit_card = [
            'issuer' => $data['issuer'],
            'vault_id' => $vault_card->id,
            'user_id' => $data['user_id'],
            'display_name' => $data['display_name'],
        ];

        return $this->repository->create($debit_card);



//        return $this->repository->create($data);


    }
}
