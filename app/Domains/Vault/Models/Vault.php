<?php

namespace App\Domains\Vault\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;

class Vault extends Model
{
    use HasFactory;

    protected $table = 'vaults';

    // Mass assignable fields
    protected $fillable = [
//        'debit_card_id',
        'details',
    ];


    /**
     * Optionally encrypt value when setting.
     */
    public function setValueAttribute($details)
    {

//        dd($details);
        $this->attributes['details'] = encrypt($details);
    }

    /**
     * Optionally decrypt value when getting.
     */
    public function getValueAttribute($details)
    {
        return decrypt($details);
    }
}
