<?php

namespace App\Domains\PaymentMethod\DebitCard\Policies;

use App\Domains\PaymentMethod\DebitCard\Models\DebitCard;
use App\Domains\User\Models\User;
use Illuminate\Auth\Access\Response;

class DebitCardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
public function viewAny(User $user): bool
    {
        // Allow users to view their own debit cards
        return true;
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DebitCard $debitCard): bool
    {
        // Users can only view their own debit cards
        return $user->id === $debitCard->user_id;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow authenticated users to create debit cards
        return true;
    }

    /**
     * Determine whether the user can update the model.
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

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DebitCard $debitCard): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DebitCard $debitCard): bool
    {
        return false;
    }
}
