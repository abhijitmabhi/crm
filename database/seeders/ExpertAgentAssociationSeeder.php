<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\User;
//use Illuminate\Support\Facades\DB;
//
//class ExpertAgentAssociationSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $experts = User::byRole('Expert')->get();
//        $agents = User::byRole('callcenter-agent')->get();
//        if ($experts->count() < 1 || $agents->count() < 1) {
//            $this->call(UserSeeder::class);
//            $experts = User::byRole('Expert')->get();
//            $agents = User::byRole('callcenter-agent')->get();
//        }
//        // All Experts got at least one agent
//        $experts->each(function ($expert) use ($agents) {
//            DB::table('callagents')->insert([
//                'expert_id' => $expert->id,
//                'agent_id' => $agents->random()->id,
//            ]);
//        });
//        // All Agents got at least one expert
//        $agents->filter(function ($agent) {
//            return DB::table('callagents')->where('agent_id', $agent->id)->doesntExist();
//        })->each(function ($agent) use ($experts) {
//            DB::table('callagents')->insert([
//                'expert_id' => $experts->random()->id,
//                'agent_id' => $agent->id,
//            ]);
//        });
//    }
//}
