<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Magnitude;
use Faker\Generator as Faker;

$factory->define(Magnitude::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));

    return [
        'name' => $faker->unique()->word(),
        'display_name' => $faker->word(),
        'measure' => $faker->randomLetter()
    ];
});
