<?php

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\Transaction\Models\DebitCardTransaction;
use App\Domains\User\Models\User;
use function Pest\Laravel\actingAs;


it('it gets transactions by user', closure: function () {

    /** @var User $user */
    $user = User::factory()->create();

    $debit_card1 = DebitCard::factory()->create([
        'user_id' => $user->id
    ]);

    $debit_card2 = DebitCard::factory()->create([
        'user_id' => $user->id
    ]);

    $payload1 = [
        'debit_card_id' => $debit_card1->id,
        'amount' => 30.00,
        'payment_reference' => 'trans1 for card1',
    ];
    DebitCardTransaction::factory()->create($payload1);

    $payload2 = [
        'debit_card_id' => $debit_card1->id,
        'amount' => 30.00,
        'payment_reference' => 'trans2 for card1',
    ];

    DebitCardTransaction::factory()->create($payload2);
    $payload3 = [
        'debit_card_id' => $debit_card1->id,
        'amount' => 30.00,
        'payment_reference' => 'trans3 for card1',
    ];
    DebitCardTransaction::factory()->create($payload3);

    $payload4 = [
        'debit_card_id' => $debit_card2->id,
        'amount' => 30.00,
        'payment_reference' => 'trans1 for card2',
    ];
    DebitCardTransaction::factory()->create($payload4);

    $payload5 = [
        'debit_card_id' => $debit_card2->id,
        'amount' => 30.00,
        'payment_reference' => 'trans2 for card2',
    ];
    DebitCardTransaction::factory()->create($payload5);

    actingAs($user)
        ->getJson('/api/debit-card-transactions')
        ->assertOk()
        ->assertJsonCount(5, 'data')
        ->assertJsonPath('data.0.payment_reference', 'trans1 for card1')
        ->assertJsonPath('data.1.payment_reference', 'trans2 for card1')
        ->assertJsonPath('data.2.payment_reference', 'trans3 for card1')
        ->assertJsonPath('data.3.payment_reference', 'trans1 for card2')
        ->assertJsonPath('data.4.payment_reference', 'trans2 for card2');
});



