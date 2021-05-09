<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//
//use LocalheroPortal\Models\User\RoleType as RoleEnum;
//use LocalheroPortal\Models\User\Role;
//use LocalheroPortal\Models\User\User;
//
//class RoleSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $permissions = [
//          'ADMIN' => [],
//          'CUSTOMER' => [],
//          'MANAGER' => [],
//          'CALLCENTER_SUPERVISOR' => ['ASSIGN_EXPERTS', 'SEARCH_LEADS'],
//          'CALLCENTER_AGENT' => ['MAKE_CALLS', 'SEARCH_LEADS'],
//          'EXPERT' => ['VIEW_APPOINTMENTS', 'SEARCH_LEADS'],
//        ];
//        foreach (RoleEnum::asArray() as $key => $roleName) {
//            factory(Role::class)->create([
//                'name' => $roleName,
//                'permissions' => $permissions[$key] ?? [],
//            ]);
//        }
//    }
//}
