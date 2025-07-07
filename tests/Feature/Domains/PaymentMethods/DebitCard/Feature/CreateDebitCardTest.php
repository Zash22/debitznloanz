<?php

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

//uses(RefreshDatabase::class);

it('allows an user to create a debit card', closure: function () {

    $user = User::factory()->create();

    $payload = [
        'user_id'  => $user->id,
        'card_number' => '4111111111111111',
        'expiry_month' => 12,
        'expiry_year' => 2030,
        'cvv' => '123',
        'issuer' => 'Test Bank',
        'display_name' => 'test-create-card',
    ];

    actingAs($user)
        ->postJson('/api/debit-cards', $payload)
        ->assertCreated()
        ->assertJsonPath('data.display_name', 'test-create-card');

    expect(DebitCard::where('user_id', $user->id)->exists())->toBeTrue();
});
