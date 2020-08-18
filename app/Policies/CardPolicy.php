<?php

namespace App\Policies;

use App\Card;
use App\User;

class CardPolicy
{
    public function checkin(User $user, Card $card): bool
    {
        $group = $card->goal->group;

        return $group->cashiers()->where('users.id', $user->id)->exists() || $group->owners()->where('users.id', $user->id)->exists();
    }

    public function update(User $user, Card $card): bool
    {
        return $card->user_id === $user->id;
    }
}
