<?php

use App\Employee;
use App\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefits = [
            [
                'name'          => 'SSS',
                'description'   => 'Description of SSS',
            ],
            [
                'name'          => 'PAGIBIG',
                'description'   => 'Description of PAGIBIG',
            ],
            [
                'name'          => 'PHILHEALTH',
                'description'   => 'Description of PHILHEALTH',
            ]
        ];

        foreach ($benefits as $benefit) {
            Benefit::create($benefit);
        };

        $employee = Employee::all('id')->random();
        $benefit = Benefit::all('id');
        $ids = $benefit->pluck('id')->all();
        $employee->benefits()->attach($ids, ['ref_number' => rand(1000000,9999999)]);

        $employee = Employee::all('id')->random();
        $employee->benefits()->attach($ids, ['ref_number' => rand(1000000,9999999)]);
    }
}