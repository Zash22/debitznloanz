<?php
use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
//uses(RefreshDatabase::class);
it('allows a user to list their debit cards', function () {
    /** @var User $user */
    $user = User::factory()->create();
    $user2 = User::factory()->create();

    Auth::login($user);

    DebitCard::factory()->create([
        'user_id' => Auth::id(),
        'display_name' => 'Auth user card 1'
    ]);

    DebitCard::factory()->create([
        'user_id' => Auth::id(),
        'display_name' => 'Auth user card 2'
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
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.display_name', 'Auth user card 1')
        ->assertJsonPath('data.1.display_name', 'Auth user card 2');
});
it('prevents users from seeing other users debit cards', function () {
    /** @var User $user1 */
    $user1 = User::factory()->create();
    /** @var User $user2 */
    $user2 = User::factory()->create();

    // Create a debit card for another user
    DebitCard::create([
        'user_id' => $user2->id,
        'display_name' => 'Other User Card',
        'issuer' => 'Test Bank',
        'vault_id' => 1
    ]);
    actingAs($user1)
        ->getJson('/api/debit-cards')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});
