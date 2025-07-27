<?php

namespace App\Domains\Vault\Models;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\Vault\Factories\VaultFactory;
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

    protected static function newFactory(): VaultFactory
    {
        return VaultFactory::new();
    }
}
