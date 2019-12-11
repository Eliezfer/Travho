<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\House;
use App\User;
use App\Address;
use Faker\Generator as Faker;

$factory->define(House::class, function (Faker $faker) {
    return [
        //
        'state' => 'Hidalgo',
        'municipality' => 'merida',
        'user_id'=>function () {
            return factory(User::class)->create()->id;
        },
        'address_id'=>function () {
            return factory(Address::class)->create()->id;
        },
        'description'=> $faker->text(30),
        'price_for_day'=>$faker-> randomFloat(2, 0, 100) ,
        'status'=>true
    ];
});
