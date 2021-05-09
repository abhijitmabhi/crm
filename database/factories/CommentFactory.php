<?php
//
///* @var $factory \Illuminate\Database\Eloquent\Factory */
//
//use Faker\Generator as Faker;
//use LocalheroPortal\Models\LeadStates;
//use LocalheroPortal\Models\Comment;
//
//$factory->define(Comment::class, function (Faker $faker) {
//    $states = array_keys(LeadStates::asArray());
//    return [
//        'body' => $faker->text(),
//        'reason' => $states[array_rand($states)],
//        'date' => $faker->date(),
//        'created_at' => $faker->dateTimeBetween(now('Europe/Berlin')->startOfWeek(), now('Europe/Berlin')->endOfWeek())
//    ];
//});
