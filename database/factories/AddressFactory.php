<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Address;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Address::class, function (Faker $faker) {
    return [
        //
        'street' => $faker->streetName,
        'cross_street1'=>$faker->streetName,
        'cross_street2'=>$faker->streetName,
        'numberHouse'=> $faker->buildingNumber ,
        'suburb'=> 'Azcorra',
        'postcode'=> $faker ->postcode
    ];
});
