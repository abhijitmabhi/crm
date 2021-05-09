<?php

namespace LocalheroPortal\Core\Http\Controllers\Api;

use LocalheroPortal\Core\Http\Controllers\Controller;
use LocalheroPortal\Models\User\User;

class UserFlagController extends Controller
{
    public function toggleActive(User $user) {
        $user->is_active = !$user->is_active;
        $user->save();
    }

    public function toggleBlockLogin(User $user) {
        $user->block_login = !$user->block_login;
        $user->save();
    }

    public function toggleDialer(User $user) {
        $user->set_option('dialer_active', !$user->get_option('dialer_active', false));
        $user->save();
    }
}
