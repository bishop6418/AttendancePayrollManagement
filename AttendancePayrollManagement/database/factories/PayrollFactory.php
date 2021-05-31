<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Payroll;
use Faker\Generator as Faker;

$factory->define(Payroll::class, function (Faker $faker) {
    return [
        'start_date' =>$faker->date($format = 'Y-m-d', $max = 'now'),
        'end_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});
