<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\home;
use Faker\Generator as Faker;

$factory->define(home::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->city,
        'contactno' => $faker->numberBetween(10000000,99999999),
        'annualincome' => $faker->randomFloat(2, 0, 999999),
        'age' => $faker->numberBetween(18,100),
        'created_at' => $faker->date('Y-m-d h:m:s', 'now'),
        'updated_at' => $faker->date('Y-m-d h:m:s', 'now')
    ];
});
