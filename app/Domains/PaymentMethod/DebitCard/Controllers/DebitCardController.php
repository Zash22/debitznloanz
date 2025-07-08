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

    /**
     * Store a new debit card.
     *
     * @param StoreDebitCardRequest $request
     * @return DebitCardResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreDebitCardRequest $request): DebitCardResource
    {
        $card = $this->service->create([
            ...$request->validated(), //php spread operator
        ]);

        return new DebitCardResource($card);
    }

    /**
     * List all debit cards for the authenticated user.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return DebitCardResource::collection($this->service->getUserDebitCards(auth()->id()));
    }
}
