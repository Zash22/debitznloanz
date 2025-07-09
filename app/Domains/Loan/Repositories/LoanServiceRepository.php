<?php

namespace App\Domains\Loan\Repositories;

use App\Domains\Loan\Models\Loan;
use App\Domains\Loan\Models\ScheduledPayment;
use App\Domains\Transaction\Models\Transaction;

class LoanServiceRepository
{
    public function markSchedulePaymentAsPaid(Transaction $transaction, ScheduledPayment $scheduledPayment)
    {
        return $scheduledPayment->update(['paid' => true , 'transaction_id' => $transaction->id, 'paid_at' => $transaction->paid_at]);
    }

    public function getLoanPayments(Loan $loan)
    {
        $loan->scheduledPayments()->where('paid', true)->sum('amount');
        $loan->scheduledPayments()->where('paid', true)->count();

        $loan->scheduledPayments()->where('paid', false)->sum('amount');
        $loan->scheduledPayments()->where('paid', false)->count();
    }
}
