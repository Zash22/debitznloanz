<?php

namespace App\Domains\PaymentMethod\DebitCard\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domains\User\Models\User;

class DebitCard extends Model
{
    protected $fillable = [
        'user_id', 'display_name', 'issuer', 'vault_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

