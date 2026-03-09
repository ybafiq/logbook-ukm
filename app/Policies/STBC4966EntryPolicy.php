<?php

namespace App\Policies;

use App\Models\STBC4966Entry;
use App\Models\User;

class STBC4966EntryPolicy
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
    public function view(User $user, STBC4966Entry $projectEntry): bool
    {
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
    public function update(User $user, STBC4966Entry $projectEntry): bool
    {
        return $user->id === $projectEntry->user_id && !$projectEntry->supervisor_approved;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, STBC4966Entry $projectEntry): bool
    {
        return $user->id === $projectEntry->user_id && !$projectEntry->supervisor_approved;
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, STBC4966Entry $projectEntry): bool
    {
        return $user->isSupervisor();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, STBC4966Entry $projectEntry): bool
    {
        return $user->isSupervisor();
    }

    public function forceDelete(User $user, STBC4966Entry $projectEntry): bool
    {
        return $user->isSupervisor();
    }
}