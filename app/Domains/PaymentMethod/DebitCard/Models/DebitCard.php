<?php

namespace App\Domains\PaymentMethod\DebitCard\Models;

use App\Domains\Vault\Models\Vault;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\User\Models\User;
use Database\Factories\DebitCardFactory;

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
     * Relationship: DebitCard belongs to a Vault.
     */
    public function vault(): BelongsTo
    {
        return $this->belongsTo(Vault::class);
    }

    protected static function newFactory(): DebitCardFactory
    {
        return DebitCardFactory::new();
    }
}
