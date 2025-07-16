<?php

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\Transaction\Models\DebitCardTransaction;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\Transaction\Factories\TransactionTypeFactory;
use App\Domains\User\Models\User;
use function Pest\Laravel\actingAs;

it('allows an authenticated user to create a debit card transaction', closure: function () {

    /** @var User $user */
    $user = User::factory()->create();

    $debit_card = DebitCard::factory()->create();

    $payload = [
        'debit_card_id' =>  $debit_card->id,
        'amount' => 30.00,
        'payment_reference' => 'for loan 123',
    ];

    actingAs($user)
        ->postJson('/api/debit-card-transactions', $payload)
        ->assertCreated()
        ->assertJsonPath('data.payment_reference', 'for loan 123');

    expect(DebitCardTransaction::where('debit_card_id', $debit_card->id)->exists())->toBeTrue();
});



