<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BookingHouse;
use App\House;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(BookingHouse::class, function (Faker $faker) {
    return [
        //
        'user_id'=>function () {
            return factory(User::class)->create()->id;
        },
        'house_id'=>function () {
            return factory(House::class)->create()->id;
        },
        'check_in'=>$faker->date,
        'check_out'=>$faker->date,
        'status' => 'in process'
    ];
});
