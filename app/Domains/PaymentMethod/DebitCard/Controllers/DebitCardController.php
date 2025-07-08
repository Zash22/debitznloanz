<?php

namespace App\Domains\PaymentMethod\DebitCard\Controllers;

use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;
use App\Domains\PaymentMethod\DebitCard\Resources\DebitCardResource;
use App\Domains\PaymentMethod\DebitCard\Requests\StoreDebitCardRequest;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DebitCardController extends Controller
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

    public function index(Request $request): AnonymousResourceCollection
    {
//        $this->authorize('viewAny');

        return DebitCardResource::collection($this->service->getUserDebitCards($request->user()->id));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return DebitCardResource
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, int $id): DebitCardResource
    {
        $debitCard = $this->service->findById($id);

        $this->authorize('view', $debitCard);

        return new DebitCardResource($debitCard);
    }

    /**
     * Store a new debit card.
     *
     * @param StoreDebitCardRequest $request
     * @return DebitCardResource
     */
    public function store(StoreDebitCardRequest $request): DebitCardResource
    {

        $card = $this->service->create([
            ...$request->validated(),
            'user_id' => $request->user()->id
        ]);
        return new DebitCardResource($card);
    }
}
