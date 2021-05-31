<?php

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Employee::class, 5)->create();

        // factory(App\Employee::class, 10)->create()->each(function ($employee) {
        //     // Seed the relation with one address
        //     $position = factory(App\Position::class)->make();
        //     $employee->position()->create(['position_id'=>$position->id]);
        
        //     // Seed the relation with 5 purchases
        //     $schedule = factory(App\Schedule::class)->make();
        //     $employee->schedule()->create(['schedule_id'=>$schedule->id]);
        
        //     $payroll = factory(App\Payroll::class)->make();
        //     $employee->payroll()->create(['payroll_id'=>$payroll->id]);
        // });
    }
}

