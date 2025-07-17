<?php

use App\Domains\Loan\Models\Loan;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Services\TransactionService;
use App\Domains\User\Models\User;
use App\Domains\Loan\Services\LoanService;
use Illuminate\Database\Eloquent\Collection;
use App\Domains\Loan\Repositories\LoanServiceRepository;
use App\Domains\Transaction\TransactionTypes\ScheduledPaymentTransactionType;
use App\Domains\Transaction\Factories\TransactionTypeFactory;

uses(Tests\TestCase::class);
//uses(RefreshDatabase::class);

beforeEach(function () {
    $transactionTypeFactory = new TransactionTypeFactory(
        new TransactionService(new TransactionTypeFactory()),
        new ScheduledPaymentTransactionType(new TransactionService(new TransactionTypeFactory()))
    );

    $this->transactionService = new TransactionService($transactionTypeFactory);
    $scheduledPaymentTransaction = new ScheduledPaymentTransactionType($this->transactionService);
    $this->loanService = new LoanService(new LoanServiceRepository(), $this->transactionService);
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

    $this->transactionService->createOriginatingTransaction('scheduled_payment', [
        'payments' => $data,
        'loan_id' => $loan->id
    ]);

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

    $this->transactionService->createOriginatingTransaction('scheduled_payment', ['payments' => $runs , 'loan_id' => $loan->id] );
    $scheduledPayment = $loan->refresh()->scheduledPayments()->first();
    $transaction = Transaction::factory()->create([
        'user_id'  => $user->id,
        'amount'   => 2000.00,
        'transaction_ref'      => 'scheduled_payment_' . $scheduledPayment->id,
        'paid_at'  => '2025-08-01',
    ]);

    $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);

    $scheduledPayment = $loan->refresh()->scheduledPayments()->first();
    expect($scheduledPayment->paid)->toBeTrue();
    expect($scheduledPayment->paid_at->toDateString())->toBe('2025-08-01');
});

test('update loan balance', function () {
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
    $scheduledPayments = $loan->scheduledPayments()->limit(2)->get();
    foreach ($scheduledPayments as $scheduledPayment) {
        $transaction = Transaction::factory()->create([
            'user_id'  => $user->id,
            'amount'   => 2000.00,
            'transaction_ref'      => 'scheduled_payment_' . $scheduledPayment->id,
        ]);
        $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);
    }
    $paidCount = $loan->refresh()->scheduledPayments()->where('paid', true)->count();
    expect($paidCount)->toBe(2);
    $this->loanService->updateLoanBalance($loan);
    $bal = $loan->refresh()->remaining_balance;
    expect($bal)->toBe(8000);
});
test('it verifies transaction before updating scheduled payment', function () {
    $user = User::factory()->create();
    $loan = Loan::factory()->create([
        'user_id' => $user->id,
        'remaining_balance' => 12000.00,
    ]);
    $scheduledPayment = $loan->scheduledPayments()->create([
        'amount' => 2000.00,
        'run_date' => '2025-08-01',
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'amount' => 2000.00,
        'transaction_ref' => 'scheduled_payment_' . $scheduledPayment->id,
    ]);
    $this->loanService->updateScheduledPayment($transaction, $scheduledPayment);
    expect($scheduledPayment->refresh()->paid)->toBeTrue();
});
test('it throws exception when transaction verification fails', function () {
    $user = User::factory()->create();
    $loan = Loan::factory()->create([
        'user_id' => $user->id,
        'remaining_balance' => 12000.00,
    ]);
    $scheduledPayment = $loan->scheduledPayments()->create([
        'amount' => 2000.00,
        'run_date' => '2025-08-01',
    ]);
    $transaction = Transaction::factory()->create([
        'user_id' => $user->id,
        'amount' => 2000.00,
        'transaction_ref' => 'scheduled_payment_999_3',
    ]);

    expect(fn() => $this->loanService->updateScheduledPayment($transaction, $scheduledPayment))
        ->toThrow(\Exception::class, 'Invalid transaction for scheduled payment');
});




