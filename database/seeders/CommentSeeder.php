<?php
//
//namespace Database\Seeders;
//
//use Illuminate\Database\Seeder;
//use LocalheroPortal\Models\User\User;
//use LocalheroPortal\Models\Lead;
//use LocalheroPortal\Models\Comment;
//use Illuminate\Support\Facades\DB;
//
//class CommentSeeder extends Seeder
//{
//    /**
//     * Run the database seeds.
//     *
//     * @return void
//     */
//    public function run()
//    {
//        $faker = \Faker\Factory::create();
//        $users = User::all();
//        if (1 > $users->count()) {
//            $this->call(UserSeeder::class);
//            $users = User::all();
//        }
//        // Comments
//        $users->each(function ($user) use ($faker) {
//            $lead = Lead::inRandomOrder()->first();
//            $comment = factory(Comment::class)->make([
//            'commentable_id' => $lead->id,
//            'commentable_type' => 'lead',
//          ]);
//            $user->comments()->save($comment);
//            DB::table('lead_user')->insert([
//            'user_id' => $user->id,
//            'lead_id' => $lead->id,
//            'time_spent' => $faker->numberBetween(10000, 300000),
//          ]);
//        });
//    }
//}
