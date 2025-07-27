<?php

use App\Domains\Loan\Models\Loan;
use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\User\Models\User;
use function Pest\Laravel\actingAs;


describe('POST /api/transaction', function () {

    test('scheduled payment is marked as paid and loan balance is updated via POST /transaction', function () {
        $user = User::factory()->create();
        $loan = Loan::factory()->create([
            'user_id' => $user->id,
            'term' => 6,
            'frequency' => 'monthly',
            'term_amount' => 2000.00,
            'principal_amount' => 12000.00,
            'remaining_balance' => 12000.00,
        ]);
        $runs = [
            [
                "run_date" => "2025-08-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
            [
                "run_date" => "2025-09-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
            [
                "run_date" => "2025-10-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
            [
                "run_date" => "2025-11-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
            [
                "run_date" => "2025-12-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
            [
                "run_date" => "2025-12-01",
                "amount" => 2000,
                "paid" => false,
                "paid_at" => null,
                "transaction_id" => null,
            ],
        ];

        foreach ($runs as $run) {
            ScheduledPayment::factory()->create([
                ...$run,'loan_id' => $loan->id

            ]);
        }

//        $this->transactionService->startTransaction('scheduled_payment', ['payments' => $runs, 'loan_id' => $loan->id]);
        $scheduledPayment = $loan->refresh()->scheduledPayments()->first();
//        dd($scheduledPayment);

        $payload = [
            'user_id' => $user->id,
            'amount' => 2000.00,
            'transaction_ref' => 'scheduled_payment_' . $scheduledPayment->id,
            'paid_at' => '2025-08-01',
        ];


        actingAs($user)
            ->postJson('/api/transaction', $payload)
            ->assertCreated()
            ->assertJsonPath('data.message', 'acknowledged');



//        $transaction = Transaction::factory()->create([
//            'user_id' => $user->id,
//            'amount' => 2000.00,
//            'transaction_ref' => 'scheduled_payment_' . $scheduledPayment->id,
//            'paid_at' => '2025-08-01',
//        ]);

//        $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);

        $scheduledPayment = $loan->refresh()->scheduledPayments()->first();
        expect($scheduledPayment->paid)->toBeTrue();
        expect($scheduledPayment->paid_at->toDateString())->toBe('2025-08-01');
        expect($loan->remaining_balance)->toBe(10000);

    });
});