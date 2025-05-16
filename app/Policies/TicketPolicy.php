<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true; //
        } elseif ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }
        return false;

    }

    public function delete(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        } elseif ($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id;
        }
        return false;

    }

    public function replace(User $user, Ticket $ticket): bool
    {
        return ($user->tokenCan(Abilities::ReplaceTicket));

    }

    public function store(User $user): bool // انا مسحت التيكيت لاني مش محتاجها هنا
    {

        return $user->tokenCan(Abilities::CreateTicket) ||
            $user->tokenCan(Abilities::CreateOwnTicket);
    }
}
