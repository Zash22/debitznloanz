<?php

namespace App\Domains\PaymentMethod\DebitCard\Controllers;

use App\Domains\PaymentMethod\DebitCard\Services\DebitCardService;
use App\Domains\PaymentMethod\DebitCard\Resources\DebitCardResource;
use App\Domains\PaymentMethod\DebitCard\Requests\StoreDebitCardRequest;
use App\Domains\PaymentMethod\Factories\PaymentMethodFactory;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DebitCardController extends Controller
{
    use AuthorizesRequests;

    protected DebitCardService $service;
    protected PaymentMethodFactory $paymentMethodFactory;

    public function __construct(DebitCardService $service, PaymentMethodFactory $paymentMethodFactory)
    {
        $this->service = $service;
        $this->paymentMethodFactory = $paymentMethodFactory;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        return DebitCardResource::collection($this->service->getUserDebitCards($request->user()->id));
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Request $request, int $id): DebitCardResource
    {
        $debitCard = $this->service->findById($id);
        $this->authorize('view', $debitCard);
        return new DebitCardResource($debitCard);
    }

    public function store(StoreDebitCardRequest $request): DebitCardResource
    {
        $strategy = $this->paymentMethodFactory->create('debit_card');
        
        $data = [
            ...$request->validated(),
            'user_id' => $request->user()->id
        ];

        $card = $strategy->create($data);
        return new DebitCardResource($card);
    }
}
