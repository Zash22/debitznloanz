<?php

use App\Domains\Loan\Models\Loan;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\User\Models\User;
use App\Domains\Loan\Services\LoanService;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Loan\Repositories\LoanServiceRepository;
//use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class);
//uses(RefreshDatabase::class);

beforeEach(function () {
    $this->loanService = new LoanService(new LoanServiceRepository());
});

test('it creates a loan', function () {
    $user = User::factory()->create();

    $loanData = [
        'user_id'          => $user->id,
        'term'             => 6,
        'frequency'        => 'monthly',
        'term_amount'      => 2000.00,
        'principal_amount' => 12000.00,
        'remaining_balance' => 12000.00,
    ];

    $loan = $this->loanService->createLoan($loanData);



    expect($loan)->toBeInstanceOf(Loan::class);
    expect($loan->scheduledPayments)->toBeInstanceOf(Collection::class);


    $this->assertDatabaseHas('loans', [
        'id'               => $loan->id,
        'user_id'          => $user->id,
        'term'             => 6,
        'frequency'        => 'monthly',
        'principal_amount' => 12000.00,
        'remaining_balance'=> 12000.00,
    ]);
});


test('it creates scheduled payments', function () {
    $user = User::factory()->create();

    $loan = Loan::factory()->create([
        'user_id'          => $user->id,
    ]);

    $data = [
        [
            "amount" => 2000.0,
            "run_date" => "2025-07-01"
        ],
        [
            "amount" => 2000.0,
            "run_date" => "2025-08-01"
        ],
        [
            "amount" => 2000.0,
            "run_date" => "2025-09-01"
        ],
        [
            "amount" => 2000.0,
            "run_date" => "2025-10-01"
        ],
        [
            "amount" => 2000.0,
            "run_date" => "2025-11-01"
        ],
        [
            "amount" => 2000.0,
            "run_date" => "2025-12-01"
        ]
    ];

    $this->loanService->saveScheduledPayments($loan->id, $data);

    $scheduledRuns = $loan->scheduledPayments()->get();

    foreach ($scheduledRuns as $key => $scheduledRun) {

        $run = new stdClass();
        $run->amount = $scheduledRun->getAttribute('amount');
        $run->run_date = $scheduledRun->getAttribute('run_date')->toDateString();

        expect($run)->toMatchObject($data[$key], 'yes');
    }

    $this->assertDatabaseHas('scheduled_payments', [
        'loan_id'               => $loan->id,
    ]);

    expect($loan->refresh()->scheduledPayments()->count())->toBe(6);
});

test('it calculates payments correctly', function () {
    $loan = Loan::factory()->create([
        'term'             => 3,
        'frequency'        => 'monthly',
        'term_amount'      => 1000.00,
        'principal_amount' => 3000.00,
        'remaining_balance'=> 3000.00,
    ]);

    $payments = $this->loanService->calculatePayments($loan);

    expect($payments)->toBeArray()->toHaveCount(3);

    foreach ($payments as $payment) {
        expect($payment)->toHaveKeys(['amount', 'run_date']);
        expect($payment['amount'])->toBe(1000.00);
    }
});

test('it generates monthly payments', function () {
    $loan = Loan::factory()->create([
        'term'             => 3,
        'frequency'        => 'monthly',
        'principal_amount' => 3000,
    ]);

    $payments = $this->loanService->calculatePayments($loan);

    expect($payments)->toHaveCount(3);

    $expectedDates = [
        '2025-08-01',
        '2025-09-01',
        '2025-10-01',
    ];

    foreach ($payments as $index => $payment) {
        expect($payment['amount'])->toBe(1000.00);
        expect($payment['run_date'])->toBe($expectedDates[$index]);
    }
});

test('it generates biweekly payments', function () {
    $loan = Loan::factory()->create([
        'term'             => 2,
        'frequency'        => 'biweekly',
        'principal_amount' => 2000,
    ]);

    $payments = $this->loanService->calculatePayments($loan);

    expect($payments)->toHaveCount(4); // 2 months * 2 payments per month

    $expected = [
        ['date' => '2025-08-01', 'amount' => 500.00],
        ['date' => '2025-08-15', 'amount' => 500.00],
        ['date' => '2025-09-01', 'amount' => 500.00],
        ['date' => '2025-09-15', 'amount' => 500.00],
    ];

    foreach ($payments as $index => $payment) {
        expect($payment['amount'])->toBe($expected[$index]['amount']);
        expect($payment['run_date'])->toBe($expected[$index]['date']);
    }
});

test('it sets scheduled payment to paid', function () {

    $user = User::factory()->create();
    $loan = Loan::factory()->create([
        'user_id'          => $user->id,
        'term'             => 6,
        'frequency'        => 'monthly',
        'term_amount'      => 2000.00,
        'principal_amount' => 12000.00,
        'remaining_balance' => 12000.00,
    ]);

    $runs =[
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

    $this->loanService->saveScheduledPayments($loan->id, $runs);

    $transaction = Transaction::factory()->create([
        'user_id'  => $user->id,
        'amount'   => 2000.00,
        'ref'      => '',
        'paid_at'  => '2025-08-01',
    ]);

    $scheduledPayment = $loan->refresh()->scheduledPayments()->first();

    $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);

    $scheduledPayment = $loan->refresh()->scheduledPayments()->first();

    expect($scheduledPayment->paid)->toBeTrue();
    expect($scheduledPayment->paid_at->toDateString())->toBe('2025-08-01');
});

test('update loan balance', function () {

    $user = User::factory()->create();

    $loanData =[
        'user_id'          => $user->id,
        'term'             => 6,
        'frequency'        => 'monthly',
        'term_amount'      => 2000.00,
        'principal_amount' => 12000.00,
        'remaining_balance' => 12000.00,
    ];

    $loan = $this->loanService->createLoan($loanData);

    $scheduledPayments = $loan->scheduledPayments()->limit(2)->get();

    foreach ($scheduledPayments as $scheduledPayment) {
        $transaction = Transaction::factory()->create([
        'user_id'  => $user->id,
        'amount'   => 2000.00,
        'ref' =>  'scheduled_payment_' . $scheduledPayment->id,
        ]);

        $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);
    }

    $paidCount = $loan->refresh()->scheduledPayments()->where('paid', true)->count();

    expect($paidCount)->toBe(2);
    $this->loanService->updateLoanBalance($loan);
    $bal = $loan->refresh()->remaining_balance;
    expect($bal)->toBe(8000);
});




