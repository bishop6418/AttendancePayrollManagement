<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Position;
use Faker\Generator as Faker;

$factory->define(Position::class, function (Faker $faker) {
        return [
            'name' => $faker->name,
            'rate_type' => $faker->word,
            'basic_salary' => rand(5000,50000),
        ];
});
