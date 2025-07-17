<?php

namespace App\Domains\Transaction\Models;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use Database\Factories\DebitCardTransactionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DebitCardTransaction extends Model
{
    /** @use HasFactory<DebitCardTransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'debit_card_id',
        'amount',
        'payment_reference',
        'transaction_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($debitCardTransaction) {
            TransactionTracking::create([
                'transaction_id' => null,
                'transaction_reference' => 'debit_card_transaction_' . $debitCardTransaction->id,
            ]);
        });
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(DebitCard::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    protected static function newFactory(): DebitCardTransactionFactory
    {
        return DebitCardTransactionFactory::new();
    }
}
