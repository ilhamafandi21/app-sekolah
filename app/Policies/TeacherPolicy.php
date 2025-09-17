<?php

namespace App\Policies;

use App\Models\Staff;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeacherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
          return $user->hasAnyRole(['admin', 'teacher', 'staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Teacher $teacher): bool
    {
         return $user->hasRole(['admin'. 'staff']) ||
         ($user->hasRole('teacher') && $user->id === $teacher->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
         return $user->hasRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Teacher $teacher): bool
    {
          return $user->hasRole(['admin', 'staff']);
     //     || ($user->hasRole('teacher') && $user->id === $teacher->user_id)
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Teacher $teacher): bool
    {
         return $user->hasRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Teacher $teacher): bool
    {
        return $user->hasRole(['admin', 'staff']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Teacher $teacher): bool
    {
         return $user->hasRole('admin');
    }
}
