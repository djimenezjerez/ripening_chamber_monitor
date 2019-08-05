<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Device;
use Faker\Generator as Faker;

$factory->define(Device::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
    $faker->addProvider(new \Faker\Provider\Internet($faker));

    return [
        'name' => $faker->unique()->stateAbbr(),
        'display_name' => $faker->word(),
        'mac' => $faker->macAddress(),
        'ip' => $faker->ipv4()
    ];
});
