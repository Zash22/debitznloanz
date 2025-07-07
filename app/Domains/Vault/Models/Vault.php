<?php

namespace App\Domains\Vault\Models;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $table = 'vaults';

    // Mass assignable fields
    protected $fillable = [
        'details',
    ];

    /**
     * Relationship: Vault belongs to a Debit Card.
     */
    public function debitCard()
    {
        return $this->hasOne(DebitCard::class);
    }
}
