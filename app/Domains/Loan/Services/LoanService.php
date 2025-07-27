<?php

namespace App\Domains\Loan\Services;

use App\Domains\Loan\Models\Loan;
use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Loan\Repositories\LoanRepository;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\Transaction\Services\TransactionService;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanService
{
    protected LoanRepository $loanRepository;
    protected TransactionService $transactionService;
    public function __construct(
        LoanRepository $loanServiceRepository,
        TransactionService $transactionService
    ) {
        $this->loanRepository = $loanServiceRepository;
        $this->transactionService = $transactionService;
    }

    public function createLoan(array $data): mixed
    {
        return DB::transaction(function () use ($data) {

            $loan = $this->loanRepository->create([...$data, 'status' => 'active', 'remaining_balance' => $data['principal_amount']]);

            $payments = $this->calculatePayments($loan);
            $this->transactionService->createOriginatingTransaction('scheduled_payment', [
                'payments' => $payments,
                'loan_id' => $loan->id
            ]);
            return $loan->refresh();
        });
    }
    public function calculatePayments(Loan $loan): array
    {
        $payments = [];

        $term = $loan->term; // in months
        $frequency = ($loan->frequency == 'monthly') ? 1 : (($loan->frequency == 'biweekly') ? 2 : null);
        $startDate = Carbon::parse($loan->created_at)->addMonths(1)->startOfMonth();

        for ($i = 0; $i < $term; $i++) {
            $runDate = $startDate->copy()->addMonths($i)->setDay(1);
            $payments[] = [
                'amount' => ($loan->term_amount / $frequency),
                'run_date' => $runDate->toDateString(),
            ];
            if ($frequency == 2) {
                $payments[] = [
                    'amount' => ($loan->term_amount / $frequency),
                    'run_date' => $runDate->copy()->setDay(15)->toDateString(),
                ];
            }
        }

        return $payments;
    }
    public function updateScheduledPayment(Transaction $transaction, ScheduledPayment $scheduledPayment)
    {
        return DB::transaction(function () use ($transaction, $scheduledPayment) {
            // Verify transaction tracking exists
            if (
                !$this->transactionService->verifyTransaction(
                    'scheduled_payment_' . $scheduledPayment->id,
                    $scheduledPayment->amount
                )
            ) {
                throw new Exception('Invalid transaction for scheduled payment');
            }

            $this->loanRepository->markSchedulePaymentAsPaid($transaction, $scheduledPayment);

            return $scheduledPayment->refresh();
        });
    }
    public function updateLoanBalance(Loan $loan)
    {
        return DB::transaction(function () use ($loan) {
            $payments = $this->loanRepository->getLoanPayments($loan);
            foreach ($payments as $payment) {
                if (
                    !$this->transactionService->getTransactionByReference(
                        'scheduled_payment_' . $payment->id
                    )
                ) {
                    abort(422, 'payments need audit');
                }
            }
            $balance = $loan->remaining_balance - $payments->sum('amount');

            $loan->remaining_balance = $balance;
            $loan->save();

            return $loan;
        });
    }
}
