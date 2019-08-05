<?php

use Faker\Generator as Faker;
use App\Employee;

$factory->define(App\User::class, function (Faker $faker) {
  return [
    'name' => $faker->sentence()->unique()
  ];
});