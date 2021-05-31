<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Employee;
use App\Payroll;
use App\Payslip;
use Faker\Generator as Faker;

$factory->define(Payslip::class, function (Faker $faker) {
    return [
        'payroll_id'        => Payroll::all()->random()->id,
        'employee_id'       => Employee::all()->random()->id,

        'total_weekdays'    => rand(10, 20),
        'counts_late'       => rand(1, 15),
        'days_absent'       => rand(1, 15),
        'days_leave'        => rand(1, 4),
        'days_worked'       => rand(1, 15),
        'days_saturday'     => rand(1, 4),

        'basic_salary'      => rand(5000, 20000),
        'additional'        => rand(500, 1000),
        'leave_pay'         => rand(100, 500),
        'saturday_pay'      => rand(100, 500),
        // 'deduc_late'        => rand(100, 500),
        // 'deduc_absent'      => rand(100, 500),

        'gross_salary'      => rand(5000, 20000),
        'total_deduction'   => rand(500, 1000),
        'net_salary'        => rand(5000, 20000),
    ];
});
