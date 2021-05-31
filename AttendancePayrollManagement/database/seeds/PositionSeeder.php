<?php

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Position::class,2)->create(); 
        //     factory(App\Employee::class)->make(['position_id'=>$position->id]);
        // });
        
    }
}
