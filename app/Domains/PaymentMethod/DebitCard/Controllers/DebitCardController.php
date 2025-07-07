<?php

namespace App\Domains\PaymentMethod\DebitCard\Controllers;

use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;
use App\Domains\PaymentMethod\DebitCard\Resources\DebitCardResource;
use App\Domains\PaymentMethod\DebitCard\Requests\StoreDebitCardRequest;
use App\Http\Controllers\Controller;

class DebitCardController extends Controller
{
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
