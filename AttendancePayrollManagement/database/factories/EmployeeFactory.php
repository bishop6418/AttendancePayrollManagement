<?php

use App\Schedule;
use App\Position;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
       
        'first_name' => $faker->name,
        'last_name' =>$faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'age' => $faker->numberBetween($min = 1, $max = 111),
        'address' => $faker->address,
        'contact_number' => '09125364879',
        'gender' => $faker->randomElement($array = array ('Male', 'Female')),
        'birthdate' => $faker->dateTimeThisYear($max = 'now', $timezone = null),
        'marital_status' => $faker->randomElement($array = array ('Single', 'Married', 'Widowed')),
        'date_hired' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'leave_credits' => rand(1,15),
        'status' => '1',
        'position_id' => function() {
            return factory(Position::class)->create()->id;
        },
        'schedule_id' => function() {
            return factory(Schedule::class)->create()->id;
        },
        
    ];
});

