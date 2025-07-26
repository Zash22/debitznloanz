<?php

namespace App\Domains\Loan\Repositories;

use App\Domains\Loan\Models\Loan;
use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Transaction\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;

class LoanServiceRepository
{
    public function markSchedulePaymentAsPaid(Transaction $transaction, ScheduledPayment $scheduledPayment)
    {
        return $scheduledPayment->update(['paid' => true , 'transaction_id' => $transaction->id, 'paid_at' => $transaction->paid_at]);
    }

    public function getLoanPayments(Loan $loan): Collection
    {
        return $loan->scheduledPayments()->where('paid', true)->get();
    }
}
