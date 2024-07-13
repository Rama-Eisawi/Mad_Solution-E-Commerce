<?php

namespace App\Policies;

use App\Models\{Permission,User};
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasRole('owner') ;
    }
}
