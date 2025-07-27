<?php

namespace App\Domains\Loan\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'term' => $this->term,
            'frequency' => $this->frequency,
            'term_amount' => $this->term_amount,
            'principal_amount' => $this->principal_amount,
            'remaining_balance' => $this->remaining_balance,
            'scheduled_payments' => $this->scheduledPayments(),
        ];
    }
}
