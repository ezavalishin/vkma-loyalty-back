<?php

namespace App\Policies;

use App\Group;
use App\User;

class GroupPolicy
{
    public function update(User $user, Group $group): bool
    {
        return $group->owners()->where('users.id', $user->id)->exists();
    }
}
