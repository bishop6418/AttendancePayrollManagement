<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Schedule;
use Faker\Generator as Faker;

$factory->define(Schedule::class, function (Faker $faker) {
    return [
        'time_in' => $faker->time($format = 'H:i:s', $max = 'now'),
        'time_out' => $faker->time($format = 'H:i:s', $max = 'now'),
        'shift' => $faker->word
    ];
});
