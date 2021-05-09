<?php
//
//namespace Database\Seeders;
//
//use Hash;
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\Role;
//use LocalheroPortal\Models\User\User;
//
///**
// * Application default DB Seeder
// */
//class DatabaseSeeder extends Seeder
//{
//    /**
//     * Seed the application's database.
//     */
//    public function run()
//    {
//        $this->call(GoogleCategoriesSeeder::class);
//        $this->call(ProductSeeder::class);
//        $this->call([
//          RoleSeeder::class,
//          UserSeeder::class,
//        ]);
//        Role::each(function ($role) {
//            $role->users()->save(factory(User::class)->make([
//            'password' => Hash::make('password'),
//          ]));
//        });
//        $this->call([
//          LeadSeeder::class,
//          ExpertAgentAssociationSeeder::class,
//          CommentSeeder::class,
//          CompanySeeder::class,
//        ]);
//    }
//}
