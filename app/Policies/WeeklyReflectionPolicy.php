<?php

namespace App\Policies;

use App\Models\WeeklyReflection;
use App\Models\User;

class WeeklyReflectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow authenticated users to view reflections
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WeeklyReflection $reflection): bool
    {
        // Users can view their own reflections, supervisors can view all
        return $user->id === $reflection->user_id || $user->isSupervisor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isStudent(); // Only students can create reflections
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WeeklyReflection $reflection): bool
    {
        // Users can update their own reflections only if not signed
        return $user->id === $reflection->user_id && !$reflection->supervisor_signed;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WeeklyReflection $reflection): bool
    {
        // Users can delete their own reflections only if not signed
        return $user->id === $reflection->user_id && !$reflection->supervisor_signed;
    }

    /**
     * Determine whether the user can sign the model.
     */
    public function sign(User $user, WeeklyReflection $reflection): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WeeklyReflection $reflection): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WeeklyReflection $reflection): bool
    {
        return $user->isSupervisor();
    }
}