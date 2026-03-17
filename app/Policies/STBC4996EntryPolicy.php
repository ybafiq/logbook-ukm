<?php

namespace App\Policies;

use App\Models\STBC4996Entry;
use App\Models\User;

class STBC4996EntryPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->id === $stbc4996Entry->user_id || $user->isSupervisor();
    }

    public function create(User $user): bool
    {
        return $user->isStudent();
    }

    public function update(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->id === $stbc4996Entry->user_id && !$stbc4996Entry->supervisor_approved;
    }

    public function delete(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->id === $stbc4996Entry->user_id && !$stbc4996Entry->supervisor_approved;
    }

    public function approve(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->isSupervisor();
    }

    public function restore(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->isSupervisor();
    }

    public function forceDelete(User $user, STBC4996Entry $stbc4996Entry): bool
    {
        return $user->isSupervisor();
    }
}
