<?php

namespace App\Policies;

use App\Models\{Order,User};
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('supervisor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('supervisor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }
}
