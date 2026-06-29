<?php

namespace App\Domains\Loan\Models;

use App\Domains\Transaction\Models\ScheduledPayment;
use App\Domains\Transaction\Models\Transaction;
use App\Domains\User\Models\User;
use App\Domains\Loan\Factories\LoanFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    /** @use HasFactory<LoanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'term',
        'frequency',
        'term_amount',
        'principal_amount',
        'remaining_balance',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scheduledPayments(): HasMany
    {
        return $this->hasMany(ScheduledPayment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    protected static function newFactory(): LoanFactory
    {
        return LoanFactory::new();
    }
}
