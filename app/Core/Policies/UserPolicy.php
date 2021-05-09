<?php

namespace LocalheroPortal\Core\Policies;

use LocalheroPortal\Models\User\Permission;
use LocalheroPortal\Models\User\User;

class UserPolicy extends Policy
{
    // public function show($loggedInUser, $userToEdit)
    // {
    //     return $loggedInUser->id === $userToEdit->id;
    // }
    // public function update($loggedInUser, $userToEdit)
    // {
    //     return $loggedInUser->id === $userToEdit->id;
    // }
    // public function destroy($user)
    // {
    //     return $user->hasPermission(Permissions::EDIT_USERS()->key);
    // }

    //only admins can index (checked in before function of parent)
    /**
     * @param  User    $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->hasPermission(Permission::EDIT_USERS()->key);
    }
    public function show(User $user)
    {
        return $user->hasPermission(Permission::EDIT_USERS()->key);
    }
    public function update(User $user)
    {
        return $user->hasPermission(Permission::EDIT_USERS()->key);
    }
}
