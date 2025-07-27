<?php

namespace App\Domains\Transaction\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionAcknowledgeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'message' => 'acknowledged',
        ];
    }
}
