<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

//uses(RefreshDatabase::class);

it('can create transaction for debit card', closure: function () {
    $response = $this->post('/api/debit-card-transactions');
    $response->assertStatus(200);

});

it('fails to create a debit card transaction due to invalid payload', function () {

});
