<?php

namespace App\Domains\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'purpose',
        'loan_id',
        'scheduled_payment_id',
        'note',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\User\Models\User::class);
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Loan\Models\Loan::class);
    }

    public function scheduledPayment(): BelongsTo
    {
        return $this->belongsTo(\App\Domains\Loan\Models\ScheduledPayment::class);
    }
}
