<?php

namespace App\Policies;

use App\Models\Rombel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RombelPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rombel $rombel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rombel $rombel): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rombel $rombel): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rombel $rombel): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rombel $rombel): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff hanya boleh kalau position = operational
        if ($user->hasRole('staff') && $user->position === 'operational') {
            return true;
        }

        return false;
    }
}
