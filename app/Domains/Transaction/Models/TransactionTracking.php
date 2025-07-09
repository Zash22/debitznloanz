<?php

namespace App\Domains\Transaction\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionTracking extends Model
{
    protected $table = 'transaction_tracking';
    protected $fillable = [
        'transaction_id',
        'reference',
    ];
    /**
     * Get the transaction that owns the tracking record.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
