<?php

namespace App\Domains\Loan\Services;

use App\Domains\Loan\Models\Loan;
use App\Domains\Loan\Models\ScheduledPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanService
{
    /**
     * Create a new loan.
     *
     * @param  array  $data
     */
    public function createLoan(array $data)
    {

        return DB::transaction(function () use ($data) {
            $loan = Loan::create([
                ...$data,
                'status' => 'active',
            ]);

//            dd($this->calculatePayments($loan));

            $this->saveScheduledPayments($loan->id, $this->calculatePayments($loan));

            return $loan->refresh();
        });
    }

    public function saveScheduledPayments($loadId, $payments):  void
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

        $principal = $loan->principal_amount;
        $term = $loan->term; // in months
        $frequency = $loan->frequency; // 'monthly' or 'biweekly'
        $startDate = Carbon::parse($loan->start_date)->startOfMonth();

        if ($frequency === 'monthly') {
            $monthlyAmount = round($principal / $term, 2);

            for ($i = 0; $i < $term; $i++) {
                $runDate = $startDate->copy()->addMonths($i)->setDay(1);
                $payments[] = [
                    'amount' => $monthlyAmount,
                    'run_date' => $runDate->toDateString(),
                ];
            }
        }

        if ($frequency === 'biweekly') {
            $biweeklyAmount = round($principal / ($term * 2), 2);

            for ($i = 0; $i < $term; $i++) {
                $month = $startDate->copy()->addMonths($i);

                $payments[] = [
                    'amount' => $biweeklyAmount,
                    'run_date' => $month->copy()->setDay(1)->toDateString(),
                ];

                $payments[] = [
                    'amount' => $biweeklyAmount,
                    'run_date' => $month->copy()->setDay(15)->toDateString(),
                ];
            }
        }

//        dd($payments);
        return $payments;
    }

}
