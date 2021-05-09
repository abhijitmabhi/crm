<?php
//
///** @var \Illuminate\Database\Eloquent\Factory $factory */
//
//use Faker\Generator as Faker;
//use LocalheroPortal\Models\Sales\SaleStatus;
//use LocalheroPortal\Models\Sales\Product;
//use LocalheroPortal\Models\Sales\Sale;
//use LocalheroPortal\Models\User\RoleType;
//use LocalheroPortal\Models\User\User;
//use LocalheroPortal\Model;
//
//$factory->define(Sale::class, function (Faker $faker) {
//    return [
//        'customer_id' => User::byRole(Roles::CUSTOMER)->select('id')->get()->random(),
//        'expert_id' => User::byRole(Roles::EXPERT)->select('id')->get()->random(),
//        'status' => SaleStatus::OPEN,
//        'product_id' => Product::all()->random(),
//        'price' => $faker->numberBetween(500, 6000),
//        'payed' => false,
//        'payment' => false
//    ];
//});
