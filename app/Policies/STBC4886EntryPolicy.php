<?php

namespace App\Policies;

use App\Models\STBC4886Entry;
use App\Models\User;

class STBC4886EntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, STBC4886Entry $entry): bool
    {
        return $user->id === $entry->user_id || $user->isSupervisor();
    }

    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function update(User $user, STBC4886Entry $entry): bool
    {
        return $user->id === $entry->user_id && !$entry->supervisor_approved;
    }

    public function delete(User $user, STBC4886Entry $entry): bool
    {
        return $user->id === $entry->user_id && !$entry->supervisor_approved;
    }

    public function approve(User $user, STBC4886Entry $entry): bool
    {
        return $user->isSupervisor();
    }

    public function restore(User $user, STBC4886Entry $entry): bool
    {
        return $user->isSupervisor();
    }

    public function forceDelete(User $user, STBC4886Entry $entry): bool
    {
        return $user->isSupervisor();
    }
}

