<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(ScheduleSeeder::class);
        // $this->call(PositionSeeder::class);
        $this->call([
            EmployeeSeeder::class,
            PayrollSeeder::class,
            // PayslipSeeder::class,
            // PayslipSeeder::class,
            BenefitSeeder::class,
            DeductionSeeder::class,
            AttendanceSeeder::class,
            LeaveSeeder::class,
            CompensationSeeder::class,
            RolesAndPermissionsSeeder::class
        ]);
        
    }
}