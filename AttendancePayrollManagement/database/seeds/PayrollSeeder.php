<?php

use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Payroll::class, 2)->create(); 
        //     factory(App\Employee::class)->make(['payroll_id'=>$payroll->id]);
        // });
    }
}
