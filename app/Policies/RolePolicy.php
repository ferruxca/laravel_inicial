<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\AuthorizationException;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasPermissionTo('role.index')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.index');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        if ($user->hasPermissionTo('role.index')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.index');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo('role.create')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if ($user->hasPermissionTo('role.edit')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if ($user->hasPermissionTo('role.destroy')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.destroy');
    }

    /**
     * Determine whether the user can restore the model.
     * (Para soft deletes)
     */
    public function restore(User $user, Role $role): bool
    {
        if ($user->hasPermissionTo('role.destroy')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.destroy');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        if ($user->hasPermissionTo('role.destroy')) {
            return true;
        }

        throw AuthorizationException::forUser($user, 'role.destroy');
    }
}