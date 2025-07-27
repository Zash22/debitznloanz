<?php

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

//uses(RefreshDatabase::class);

describe('POST /api/debit-cards', function () {
    it('allows an authenticated user to create a debit card', closure: function () {
        /** @var User $user */
        $user = User::factory()->create();

        $payload = [
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

    it('fails to create a debit card due to invalid payload', function () {
        /** @var User $user */
        $user = User::factory()->create();

        $invalidPayload = [
            'expiry_month'  => 13,
            'expiry_year'   => 2030,
            'cvv'           => '12',
            'issuer'        => '',
            'display_name'  => null,
        ];

        actingAs($user)
            ->postJson('/api/debit-cards', $invalidPayload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'card_number',
                'expiry_month',
                'cvv',
                'issuer',
                'display_name',
            ]);
    });

    it('fails to create a debit card due to unauthenticated user', function () {
        $payload = [
            'card_number' => '4111111111111111',
            'expiry_month' => 12,
            'expiry_year' => 2030,
            'cvv' => '123',
            'issuer' => 'Test Bank',
            'display_name' => 'test-create-card',
        ];

        $this->postJson('/api/debit-cards', $payload)
            ->assertUnauthorized();
    });
});
