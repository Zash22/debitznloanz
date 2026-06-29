<?php
use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
//uses(RefreshDatabase::class);

describe('GET /api/debit-cards', function () {
    it('allows a user to list their debit cards', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        DebitCard::factory()->create([
            'user_id' => $user->id,
            'display_name' => 'Auth user card 1'
        ]);

        DebitCard::factory()->create([
            'user_id' => $user->id,
            'display_name' => 'Auth user card 2'
        ]);

        DebitCard::factory()->create([
            'user_id' => $user->id,
            'display_name' => 'Auth user card 3'
        ]);

        DebitCard::factory()->create([
            'user_id' => $user2->id,
            'display_name' => 'User 2 card 1'
        ]);

        DebitCard::factory()->create([
            'user_id' => $user2->id,
            'display_name' => 'User 2 card 2'
        ]);

        actingAs($user)
            ->getJson('/api/debit-cards')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.display_name', 'Auth user card 1')
            ->assertJsonPath('data.1.display_name', 'Auth user card 2')
            ->assertJsonPath('data.2.display_name', 'Auth user card 3');
    });

    it('allows a user to view their own debit card', function () {
        /** @var User $user */
        $user = User::factory()->create();

        $debitCard = DebitCard::factory()->create([
            'user_id' => $user->id,
            'display_name' => 'My Test Card'
        ]);

        actingAs($user)
            ->getJson("/api/debit-cards/$debitCard->id")
            ->assertOk()
            ->assertJsonPath('data.display_name', 'My Test Card');
    });

    it('prevents a user from viewing another users debit card', function () {
        /** @var User $user */
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $debitCard = DebitCard::factory()->create([
            'user_id' => $otherUser->id,
            'display_name' => 'Other User Card'
        ]);
        actingAs($user)
            ->getJson("/api/debit-cards/{$debitCard->id}")
            ->assertForbidden();
    });

    it('returns not found for non-existent debit card', function () {
        /** @var User $user */
        $user = User::factory()->create();
        actingAs($user)
            ->getJson('/api/debit-cards/999')
            ->assertNotFound();
    });
});






//it('prevents users from seeing other users debit cards', function () {
//    /** @var User $user1 */
//    $user1 = User::factory()->create();
//    /** @var User $user2 */
//    $user2 = User::factory()->create();
//
//    // Create a debit card for another user
//    DebitCard::create([
//        'user_id' => $user2->id,
//        'display_name' => 'Other User Card',
//        'issuer' => 'Test Bank',
//        'vault_id' => 1
//    ]);
//    actingAs($user1)
//        ->getJson('/api/debit-cards')
//        ->assertOk()
//        ->assertJsonCount(0, 'data');
//});
