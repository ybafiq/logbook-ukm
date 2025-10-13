<?php

namespace App\Policies;

use App\Models\ProjectEntry;
use App\Models\User;

class ProjectEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow authenticated users to view project entries
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjectEntry $projectEntry): bool
    {
        // Users can view their own entries, supervisors can view all
        return $user->id === $projectEntry->user_id || $user->isSupervisor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isStudent(); // Only students can create entries
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjectEntry $projectEntry): bool
    {
        // Users can update their own entries only if not approved
        return $user->id === $projectEntry->user_id && !$projectEntry->supervisor_approved;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectEntry $projectEntry): bool
    {
        // Users can delete their own entries only if not approved
        return $user->id === $projectEntry->user_id && !$projectEntry->supervisor_approved;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, ProjectEntry $projectEntry): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectEntry $projectEntry): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectEntry $projectEntry): bool
    {
        return $user->isSupervisor();
    }
}