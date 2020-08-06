<?php

namespace App\Events;

use App\Group;

class GroupCreated extends Event
{
    public Group $group;

    /**
     * Create a new event instance.
     *
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }
}
