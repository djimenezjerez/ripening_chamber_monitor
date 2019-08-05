<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Measurement;
use App\Room;
use App\Magnitude;
use Faker\Generator as Faker;

$factory->define(Measurement::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\DateTime($faker));
    $date = $faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now', $timezone = null);

    return [
        'room_id' => Room::all()->random()->id,
        'magnitude_id' => Magnitude::all()->random()->id,
        'value' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
        'created_at' => $date,
        'updated_at' => $date
    ];
});
