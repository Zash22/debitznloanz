<?php

namespace App\Domains\PaymentMethod\DebitCard\Policies;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DebitCardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view all their cards.
     */
    public function viewAny(User $user): bool
    {
        return true;

    //        dd(Response::allow());
    //        return Response::allow();
    }
    /**
     * Determine whether the user can view a specific card.
     */
    public function view(User $user, DebitCard $debitCard): Response
    {

        return $user->id === $debitCard->user_id ? Response::allow() : Response::deny('You do not have permission to view this card.')->withStatus(403);
    }
    /**
     * Determine whether the user can create cards.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update their card.
     */
    public function update(User $user, DebitCard $debitCard): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DebitCard $debitCard): bool
    {
        return false;
    }
}
