<?php

namespace App\Domains\Loan\Services;

use App\Domains\Loan\Models\Loan;
use App\Domains\Loan\Models\ScheduledPayment;
use App\Domains\Loan\Repositories\LoanServiceRepository;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanService
{
    protected LoanServiceRepository $loanServiceRepository;
    public function __construct(LoanServiceRepository $loanServiceRepository)
    {
        $this->loanServiceRepository = $loanServiceRepository;
    }

    /**
     * Create a new loan.
     *
     * @param  array  $data
     */
    public function createLoan(array $data)
    {

        return DB::transaction(function () use ($data) {

//            dd($data);
            $loan = Loan::create([
                ...$data,
                'status' => 'active',
            ]);

            $this->saveScheduledPayments($loan->id, $this->calculatePayments($loan));

            return $loan->refresh();
        });
    }

    public function saveScheduledPayments($loadId, $payments): void
    {
        foreach ($payments as $payment) {
            ScheduledPayment::create([
                'loan_id' => $loadId,
                'amount' => $payment['amount'],
                'run_date' => $payment['run_date'],
            ]);
        }
    }

    public function calculatePayments(Loan $loan): array
    {
        $payments = [];

        $term = $loan->term; // in months
        $frequency = ($loan->frequency == 'monthly') ? 1 : (($loan->frequency == 'biweekly') ? 2 : null);
        $startDate = Carbon::parse($loan->start_date)->startOfMonth();

        for ($i = 0; $i < $term; $i++) {
            $runDate = $startDate->copy()->addMonths($i)->setDay(1);
            $payments[] = [
                'amount' =>  ($loan->term_amount / $frequency),
                'run_date' => $runDate->toDateString(),
            ];
            if ($frequency == 2) {
                $payments[] = [
                    'amount' =>  ($loan->term_amount / $frequency),
                    'run_date' => $runDate->copy()->setDay(15)->toDateString(),
                ];
            }
        }

        return $payments;
    }

    public function updateScheduledPayment(Transaction $transaction, ScheduledPayment $scheduledPayment)
    {
        $this->loanServiceRepository->markSchedulePaymentAsPaid($transaction, $scheduledPayment);
    }

    public function updateLoanBalance(Loan $loan)
    {
        $payments = $this->loanServiceRepository->getLoanPayments($loan);
    }
}
