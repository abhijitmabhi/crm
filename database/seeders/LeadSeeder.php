<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\Role;
//use LocalheroPortal\Models\User\User;
//use LocalheroPortal\Models\Lead;
//use UserSeeder;
//
//class LeadSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $experts = User::byRole('Expert');
//        if (1 > Role::whereName('Expert')->count() || 1 > $experts->count()) {
//            $this->call(UserSeeder::class);
//            $experts = User::byRole('Expert');
//        }
//        $experts->each(function ($expert) {
//            for ($i = 0; $i<100;$i++) {
//                if ($i < 80) {
//                    $expert
//                        ->leads()
//                        ->save(factory(Lead::class)->make());
//                } else {
//                    $expert->leads()->save(factory(Lead::class)->make([
//                        'blocked' => true
//                    ]));
//                }
//            }
//        });
//    }
//}
