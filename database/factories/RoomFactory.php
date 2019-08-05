<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Room;
use App\Device;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));
    $faker->addProvider(new \Faker\Provider\en_US\Address($faker));

	return [
		'name' => $faker->unique()->stateAbbr(),
		'display_name' => $faker->word(),
		'device_id' => Device::all()->random()->id
	];
});
