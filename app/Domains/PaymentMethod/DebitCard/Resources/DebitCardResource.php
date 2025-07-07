<?php

namespace App\Domains\PaymentMethod\DebitCard\Resources;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DebitCard
 */
class DebitCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'created_at' => $this->created_at,
        ];
    }
}
