<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $user, Ticket $ticket): bool
    {
        return ($user->tokenCan(Abilities::UpdateUser));

    }
    public function delete(User $user, Ticket $ticket): bool
    {
        return ($user->tokenCan(Abilities::DeleteUser));
    }
    public function replace(User $user, Ticket $ticket): bool
    {
        return ($user->tokenCan(Abilities::ReplaceUser));
    }
    public function store(User $user): bool // انا مسحت التيكيت لاني مش محتاجها هنا
    {
        return ($user->tokenCan(Abilities::CreateUser));
    }
}
