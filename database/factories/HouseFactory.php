<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\House;
use Faker\Generator as Faker;

$factory->define(House::class, function (Faker $faker) {
    return [
        //
        'country'=> $faker->country,
        'state' => $faker->state,
        'municipality' => 'merida',
        'user_id'=>'4',
        'address_id'=>'2',
        'description'=> $faker->text(30),
        'price_for_day'=>$faker-> randomFloat(2, 0, 100) ,
        'status'=>true
    ];
});
