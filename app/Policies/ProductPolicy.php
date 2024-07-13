<?php

namespace App\Policies;

use App\Models\{Product, User};
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function __construct()
    {
        //print(5);
    }
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
    public function view(User $user, Product $product): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin') || $user->hasRole('supervisor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('owner') || $user->hasRole('super-admin') || $user->hasRole('admin');
    }
}
