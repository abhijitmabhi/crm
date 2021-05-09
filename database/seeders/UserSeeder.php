<?php
//
//namespace Database\Seeders;
//
//use Hash;
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\Role;
//use LocalheroPortal\Models\User\User;
//
//class UserSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $roles = Role::all();
//        if (1 > $roles->count()) {
//            $this->call(RoleSeeder::class);
//            $roles = Role::all();
//        }
//        $roles->each(function ($role) {
//            $role->users()->save(
//                factory(User::class)->make([
//                    'password' => Hash::make('password'),
//                    'email' => $role->name . "@example.com"
//                ])
//            );
//        });
//    }
//}
