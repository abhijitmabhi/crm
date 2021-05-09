<?php

namespace Tests\ModelDummyTraits;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\User\User;

trait TestingUserTrait
{

    public function createTestUser(int $role=7) {

        $user = new User;
        $user->first_name = "TestUserFirstName";
        $user->last_name = "TestUserLastName";
        $user->email = "TestUserEmail@gmail.com";
        $user->email_verified_at = now();
        $user->avatar = null;
        $user->password = Hash::make("TestUserPassword");
        $user->api_token = null;
        $user->remember_token = null;
        $user->created_at = now();
        $user->updated_at = now();
        $user->deleted_at = null;
        $user->options = null;
        $user->is_active = 1;
        $user->save();

        //Expert User = 7
        DB::table('role_user')->insert(
            ['role_id' => $role, 'user_id' => $user->id]
        );

        return $user;
    }


    //TODO Review: DB Zugriffe hier richtig? ODer lieber in ein Repository? Bin mir nicht sicher, weil dann
    //wieder so viele kleine Zugriffe stattfinden, hattest du ja mal angemerkt
    //Außerdem gehört das logisch ja eigentlich hierhin
    public function deleteTestUser(User $user) {
        Comment::query()->where('user_id', '=', $user->id)->delete();
        DB::table('role_user')->where('user_id', '=', $user->id)->delete();
        $user->forceDelete();
    }
}
