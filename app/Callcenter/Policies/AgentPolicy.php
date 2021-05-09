<?php

namespace LocalheroPortal\Callcenter\Policies;

use LocalheroPortal\Models\User\Permission;
use LocalheroPortal\Core\Policies\Policy;
use LocalheroPortal\Models\User\User;

/**
 * Used to Authenticate Callagent related Controller actions
 */
class AgentPolicy extends Policy
{
    /**
     * authorizes index requests
     * @param  User $user           [description]
     * @return bool [description]
     */

    public function index(User $user): bool
    {
        $user->roles();
        return $user->hasPermission(Permission::MAKE_CALLS()->key);
    }
}