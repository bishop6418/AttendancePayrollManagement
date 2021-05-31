<?php

// namespace App\Services;

// use App\Attendance;
// use App\Schedule;
// use App\Http\Controllers\AttendanceController;
// use Throwable;
// use App\Employee;

// class Check_Attendance
// {
//     public function time_in()
//     {
//         $time_in = date('H:i:s', strtotime($time_in));
//         $time_out = date('H:i:s', strtotime($time_out));
//         $date = date("Y-m-d");
//         $employee = Employee::where('id', $id);
//         if($employee->num_rows < 1)
//         {
//             $_SESSION['error'] = 'Employee not found';
//         }

//         else{
//             $emp_row = $employee->all();
//             $emp_id = $emp_row['id'];

//             $attendance = Attendance::where('employee_id', $emp_id)->where('date', $date);
//             if($attendance->num_rows > 0){
// 				$_SESSION['error'] = 'Employee attendance for the day exist';
// 			}   

//             $sched_id = $emp_row['schedule_id'];
//             $schedule = Schedule::where('id', $sched_id);

//             $sched_row = $schedule->all();
            

//         }
//     }
// }