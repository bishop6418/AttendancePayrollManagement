<?php

use App\Deduction;
use App\Employee;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deductions = [
            [
                'deduction_name' => 'late',
                'description'   => 'Description of late',
                'value'          => rand(30,200),
                'value_type'        => 0.5
                
            ],
            [
                'deduction_name' => 'absent',
                'description'   => 'Description of absent',
                'value'          => rand(300,2000),
                'value_type'        => 0.1
            ]
        ];

        foreach ($deductions as $deduction) {
            Deduction::create($deduction);
        };

        $employee = Employee::all('id')->random();
        $deduction = Deduction::all('id')->random();
        $employee->deductions()->attach($deduction, ['amount' 
        => rand(300,1000), 'date_deducted' 
        => new DateTime()]);
        
        $employee = Employee::all('id')->random();
        $deduction = Deduction::all('id')->random();
        $employee->deductions()->attach($deduction, ['amount' 
        => rand(300,1000), 'date_deducted' 
        => new DateTime()]);
    }
}
