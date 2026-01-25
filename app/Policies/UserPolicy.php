<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the given user can approve other users.
     */
    public function approve(User $actor, User $user): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Determine whether the given user can reject other users.
     */
    public function reject(User $actor, User $user): bool
    {
        return $actor->isAdmin();
    }

    /**
     * Determine whether the user can view any users (admin dashboard).
     */
    public function viewAny(User $actor): bool
    {
        return $actor->isAdmin();
    }
}
