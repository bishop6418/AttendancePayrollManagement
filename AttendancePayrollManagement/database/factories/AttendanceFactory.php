<?php

use App\Employee;
use App\Attendance;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'time_in_am'       => $faker->time($format = 'H:i:s', $max = 'now'),
        'time_out_am'      => $faker->time($format = 'H:i:s', $max = 'now'),
        'time_in_pm'       => $faker->time($format = 'H:i:s', $max = 'now'),
        'time_out_pm'      => $faker->time($format = 'H:i:s', $max = 'now'),
        'status_am'        => $faker->randomElement($array = array ('on-time', 'late')),
        'status_pm'        => $faker->randomElement($array = array ('on-time', 'late')),
        'date'          => date("Y-m-d"),
        'employee_id'   => Employee::all()->random()->id
    ];
});
