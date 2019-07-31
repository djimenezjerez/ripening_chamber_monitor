<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Device;
use Faker\Generator as Faker;

$factory->define(Device::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));

    return [
        'name' => $faker->word(),
        'display_name' => $faker->word(),
        'mac' => $faker->ipv4(),
        'ip' => $faker->macAddress()
    ];
});
