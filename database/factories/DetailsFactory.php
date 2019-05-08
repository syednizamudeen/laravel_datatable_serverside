<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model\Detail;
use Faker\Generator as Faker;

$factory->define(Detail::class, function (Faker $faker) {
    return [
        'home_id' => factory(App\Model\home::class)->create()->id,
        'spouse' => $faker->name,
        'email' => $faker->unique()->email,
        'gender' => $faker->randomElement($array = array ('m','f')),
        'created_at' => $faker->date('Y-m-d h:m:s', 'now'),
        'updated_at' => $faker->date('Y-m-d h:m:s', 'now')
    ];
});
