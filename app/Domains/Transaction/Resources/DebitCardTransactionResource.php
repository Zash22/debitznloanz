<?php

namespace App\Domains\Transaction\Resources;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DebitCard
 */
class DebitCardTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'transaction_id' => $this->id,
            'debit_card_id' => $this->debit_card_id,
            'payment_reference' => $this->payment_reference,
            'created_at' => $this->created_at,
        ];
    }
}
