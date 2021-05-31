<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Benefit;
use App\Compensation;
use Faker\Generator as Faker;

$factory->define(Compensation::class, function (Faker $faker) {
    return [
        'from_amount' => 1500,
        'to_amount' => 10000,
        'employee_share' => 1.0,
        'employer_share'=> 3.0,
        'benefit_id' => Benefit::all()->random()->id
    ];
});
