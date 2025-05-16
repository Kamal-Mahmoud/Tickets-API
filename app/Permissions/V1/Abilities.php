<?php

namespace App\Permissions\V1;

use App\Models\User;

final class Abilities
{
    public const UpdateTicket = 'ticket:update';
    public const ReplaceTicket = 'ticket:replace';
    public const CreateTicket = 'ticket:replace';
    public const DeleteTicket = 'ticket:delete';


    public const  CreateOwnTicket = 'ticket:own:create';
    public const  UpdateOwnTicket = 'ticket:own:update';
    public const  DeleteOwnTicket = 'ticket:own:delete';


    public const CreateUser = 'user:create';
    public const  DeleteUser = 'user:delete';
    public const  UpdateUser = 'user:update';
    public const ReplaceUser = 'user:replace';


    public static function getAbilities(User $user)
    {
        if ($user->is_manager) { // check in DB IF the user submitted manager or NOT..
            return [
                self::UpdateTicket,
                self::ReplaceTicket,
                self::CreateTicket,
                self::DeleteTicket,
                self::CreateUser,
                self::ReplaceUser,
                self::UpdateUser,
                self::DeleteUser,
            ];
        } else {
            return [
                self::CreateOwnTicket,
                self::UpdateOwnTicket,
                self::DeleteOwnTicket
            ];
        }
    }

}
