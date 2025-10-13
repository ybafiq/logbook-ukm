<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all users, supervisor can view students, student can view only themselves
        return $user->isAdmin() || $user->isSupervisor() || $user->isStudent();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin can view all users
        if ($user->isAdmin()) {
            return true;
        }
        
        // Supervisor can view students but not admin details
        if ($user->isSupervisor()) {
            return $model->isStudent();
        }
        
        // Students can only view their own details
        if ($user->isStudent()) {
            return $user->id === $model->id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin can update all users except other admins (unless same user)
        if ($user->isAdmin()) {
            return !$model->isAdmin() || $user->id === $model->id;
        }
        
        // Users can update their own profile
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Only admin can soft delete users, but not themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        // Only admin can restore soft deleted users
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        // Only admin can permanently delete users, but not themselves
        return $user->isAdmin() && $user->id !== $model->id;
    }
    
    /**
     * Determine whether the user can view trashed users.
     */
    public function viewTrashed(User $user): bool
    {
        // Only admin can view soft deleted users
        return $user->isAdmin();
    }
}
