<?php

use App\Domains\User\Models\User;

describe('POST /api/loans', function () {
    it('creates a loan successfully', function () {
        $user = User::factory()->create();
        $payload = [
            'term' => 6,
            'frequency' => 'monthly',
            'term_amount' => 2000.00,
            'principal_amount' => 12000.00,
        ];

        $response = $this
            ->actingAs($user)
            ->postJson('/api/loans', $payload);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'user_id',
                'term',
                'frequency',
                'term_amount',
                'principal_amount',
                'remaining_balance',
                'scheduled_payments',
            ]
        ]);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'term' => 6,
            'frequency' => 'monthly',
            'term_amount' => 2000.00,
            'principal_amount' => 12000.00,
            'remaining_balance' => 12000.00,
        ]);
    });
});
