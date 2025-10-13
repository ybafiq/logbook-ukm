<?php

namespace App\Policies;

use App\Models\LogEntry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LogEntryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LogEntry $logEntry): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->logEntries()->count() <2;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LogEntry $logEntry): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LogEntry $logEntry): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LogEntry $logEntry): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LogEntry $logEntry): bool
    {
        return false;
    }
}
