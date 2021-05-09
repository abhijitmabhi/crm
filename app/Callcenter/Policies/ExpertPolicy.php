<?php

namespace LocalheroPortal\Callcenter\Policies;

use LocalheroPortal\Models\User\Permission;
use LocalheroPortal\Core\Policies\Policy;
use LocalheroPortal\Models\User\User;

/**
 * Used to authorize Expert related Requests
 *
 * TODO: Add functions for all CRUD Methods
 */

class ExpertPolicy extends Policy
{
    /**
     *
     * @param  User $user           [description]
     * @return bool [description]
     */
    public function administer(User $user): bool
    {
        return $user->hasPermission(Permission::ASSIGN_EXPERTS()->key);
    }

    /**
     * @param  User   $user
     * @return bool
     */
    public function index(User $user): bool
    {
        return $user->hasPermission(Permission::VIEW_EXPERTS()->key);
    }

    /**
     * [show description]
     * @param  User $user           [description]
     * @return bool [description]
     */

    public function show(User $user): bool
    {
        return $user->hasPermission(Permission::VIEW_APPOINTMENTS()->key);
    }
}