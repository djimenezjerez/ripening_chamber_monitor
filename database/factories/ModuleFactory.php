<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Module;
use Faker\Generator as Faker;

$factory->define(Module::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));

    return [
        'name' => $faker->unique()->word()
    ];
});
