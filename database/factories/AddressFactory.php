<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Address;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Address::class, function (Faker $faker) {
    return [
        //
        'country'=> $faker->country,
        'state' => $faker->state,
        'municipality' => 'merida',
        'street' => $faker->streetName,
        'crossStreet1'=>$faker->streetName,
        'crossStreet2'=>$faker->streetName,
        'numberHouse'=> $faker->buildingNumber ,
        'suburb'=> 'Azcorra',
        'postcode'=> $faker ->postcode
    ];
});
