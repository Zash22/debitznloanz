<?php

namespace App\Domains\Loan\Controllers;

use App\Domains\Loan\Requests\StoreLoanRequest;
use App\Domains\Loan\Resources\LoanResource;
use App\Domains\Loan\Services\LoanService;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LoanController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private readonly LoanService $service)
    {
    }

    public function store(StoreLoanRequest $request): LoanResource
    {
        $data = [
            ...$request->validated(),
            'user_id' => $request->user()->id
        ];
        $loan = $this->service->createLoan($data);
        return new LoanResource($loan);
    }
}
