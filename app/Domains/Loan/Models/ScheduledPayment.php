<?php

namespace App\Domains\Loan\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledPayment extends Model
{
    protected $fillable = [
        'loan_id',
        'run_date',
        'amount',
        'paid',
        'paid_at',
        'transaction_id',
    ];

    protected $casts = [
        'run_date' => 'date',
        'paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Transaction\Models\Transaction::class);
    }
}
