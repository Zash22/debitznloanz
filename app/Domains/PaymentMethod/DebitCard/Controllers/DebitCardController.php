<?php

namespace App\Domains\PaymentMethod\DebitCard\Controllers;

use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;
use App\Domains\PaymentMethod\DebitCard\Resources\DebitCardResource;
use App\Domains\PaymentMethod\DebitCard\Requests\StoreDebitCardRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;

class DebitCardController extends BaseController
{
    use AuthorizesRequests;
    /**
     * @var DebitCardService
     */
    protected DebitCardService $service;

    public function __construct(DebitCardService $service)
    {
        $this->service = $service;
    }

    public function store(StoreDebitCardRequest $request): DebitCardResource
    {
        $card = $this->service->create([
            ...$request->validated(), //php spread operator
        ]);

        return new DebitCardResource($card);
    }
}
