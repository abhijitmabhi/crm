<?php


namespace LocalheroPortal\Core\Repository;

use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;

class UserRepository {

    public static function getAllExperts() {
        return User::byRole(RoleType::EXPERT)->get();
    }

    public function getUserByEmail($email) {
        return User::whereEmail($email)->first();
    }

}
