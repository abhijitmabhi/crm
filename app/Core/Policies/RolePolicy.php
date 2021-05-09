<?php

namespace LocalheroPortal\Core\Policies;

use LocalheroPortal\Models\User\Permission;
use LocalheroPortal\Models\User\User;

class RolePolicy extends Policy
{
    public function index(User $user)
    {
        return $user->hasPermission(Permission::EDIT_ROLES);
    }

    public function destroy(User $user)
    {
        return $user->hasPermission(Permission::EDIT_ROLES);
    }

    public function update(User $user)
    {
        return $user->hasPermission(Permission::EDIT_ROLES);
    }

    public function create(User $user)
    {
        return $user->hasPermission(Permission::EDIT_ROLES);
    }

    public function store(User $user)
    {
        return $user->hasPermission(Permission::EDIT_ROLES);
    }
}
