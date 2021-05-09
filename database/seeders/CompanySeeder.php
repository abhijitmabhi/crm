<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\RoleType;
//use LocalheroPortal\Models\User\Role;
//use LocalheroPortal\Models\User\User;
//use LocalheroPortal\Models\LLI\Company;
//
//class CompanySeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $role = Role::whereName(Roles::CUSTOMER)->first();
//        if (empty($role)) {
//            $role = factory(Role::class)->create(['name' => Roles::CUSTOMER]);
//        }
//        $users = factory(User::class, 100)->create();
//        foreach ($users as $user) {
//            $user->roles()->save($role);
//
//            $company = factory(Company::class)->make();
//            $user->company()->save($company);
//
//        }
//    }
//}
