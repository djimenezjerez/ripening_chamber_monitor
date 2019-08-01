<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Room;
use App\Device;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Lorem($faker));

	return [
		'name' => $faker->word(),
		'display_name' => $faker->sentence(),
		'device_id' => Device::all()->random()->id
	];
});
