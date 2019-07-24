<?php

use Faker\Generator as Faker;
use App\Employee;

$factory->define(App\User::class, function (Faker $faker) {
  return [
    'username' => $faker->unique()->firstname,
    'charge' => mb_strtoupper($faker->unique()->catchPhrase),
    'password' => Hash::make('secret'),
  ];
});