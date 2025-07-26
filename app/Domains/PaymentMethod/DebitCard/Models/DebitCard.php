<?php

namespace App\Domains\PaymentMethod\DebitCard\Models;

use App\Domains\PaymentMethod\DebitCard\Policies\DebitCardPolicy;
use App\Domains\Transaction\Models\DebitCardTransaction;
use App\Domains\Vault\Models\Vault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\User\Models\User;
use Database\Factories\DebitCardFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

#[UsePolicy(DebitCardPolicy::class)]
class DebitCard extends Model
{
    /** @use HasFactory<DebitCardFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'display_name', 'issuer', 'vault_id'
    ];

    /**
     * Relationship: DebitCard belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: DebitCard has one Vault entry
     */
    public function vault(): HasOne
    {
        return $this->hasOne(Vault::class);
    }

    public function transactions(): HasOneOrMany
    {
        return $this->hasMany(DebitCardTransaction::class);
    }

    protected static function newFactory(): DebitCardFactory
    {
        return DebitCardFactory::new();
    }
}
