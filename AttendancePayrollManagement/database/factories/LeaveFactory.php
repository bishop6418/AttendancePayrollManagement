<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use App\Leave;
use Faker\Generator as Faker;

$factory->define(Leave::class, function (Faker $faker) {
    return [
        'from' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'to' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'status' => $faker->randomElement($array = array ('Approved', 'Denied')),
        'no_of_days' => $faker->numberBetween($min = 1, $max = 10),
        'description' => "May sakit ka",
        'employee_id'   => Employee::all()->random()->id
    ];
});