<?php

namespace App\Http\Controllers;

include 'Timezone.php';

use App\Attendance;
use App\Employee;
use App\Schedule;
use App\Leave;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use League\CommonMark\Inline\Parser\EscapableParser;
use LengthException;
use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Type\Time;

class AttendanceController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $employee = Employee::where('id', $request->all())->first();
        $schedule = Schedule::where('id', $employee->schedule_id )->first();
        $leave = Leave::where('from', date("Y-m-d"))->where('status', 'Approved')->get('employee_id');
        $attendance = Attendance::where('date', date("Y-m-d"))->get('employee_id');
        $time_now = Carbon::parse(date("H:i:s"));

        //Set all Employees who have approved leave in Leave Table to Attendace
        if(count($attendance) == 0){
            foreach($leave as $leave_empId)
            {
                $attendance = new Attendance();
                $attendance->employee_id = $leave_empId->employee_id;
                $attendance->status_am = 'on-leave';
                $attendance->status_pm = 'on-leave';
                $attendance->date = date("Y-m-d");
                $attendance->save();
                return response()->json(['message'=>'Success','data'=>$attendance]);
            }
            goto A;
        }

        else {
            A:
            if(($time_now->format("H:i:s") > '07:00:00') && ($time_now->format("H:i:s") < '12:30:00')){
                if($employee->count() > 0){
                    $attendance = Attendance::where('date', date("Y-m-d"))
                    ->where('employee_id', $request->employee_id)
                    ->first();
                    if($attendance != null){
                        if(($time_now->format("H:i:s") > '11:50:00')){
                            $employee_id = Employee::all('id');
                            $attendance_id = Attendance::where('date', date("Y-m-d"))->get('employee_id');
                            $ctr = null;
                            for($x = 0; $x < $employee_id->count(); $x++)
                            {
                                for($y = 0; $y < $attendance_id->count(); $y++)
                                {
                                    if($attendance_id[$y]->employee_id == $employee_id[$x]->id){
                                        $ctr++;
                                    }
                                }
                                if($ctr == null){
                                    $attendance = new Attendance();
                                    $attendance->employee_id = $employee_id[$x]->id;
                                    $attendance->status_am = 'absent';
                                    $attendance->date = date("Y-m-d");
                                    $attendance->save();
                                }
                                $ctr = 0;
                            }
                            $attend = Attendance::where('employee_id', $request->employee_id)
                            ->get('time_in_am');
                            if($attend != null){
                                Attendance::where('employee_id', $request->employee_id)
                                ->where('date', date("Y-m-d"))
                                ->update(['time_out_am' => date("H:i:s")]);
                                return response()->json(['message'=>'success']);
                            }
                            else{
                                return response()->json(['message'=>'You cannot time out this morning! you did not Time in before cut off. Notify HR']);
                            }
                        }
                        else{
                            $grace_time = Carbon::parse($schedule->time_in)->addMinutes(21);
                            $logstatus = ($time_now->format("H:i:s") < $grace_time->format("H:i:s")) ? 'on-time' : 'late';
                            $attendance_stats = Attendance::where('employee_id', $request->employee_id)
                            ->where('status_am', 'on-leave')->first('status_am');
                            if($attendance_stats->status_am == 'on-leave'){
                                Attendance::where('employee_id', $request->employee_id)
                                ->where('date', date("Y-m-d"))
                                ->update(['status_am' => $logstatus]);
                                return response()->json(['message'=>'Your Leave this day is void beacause you logged in Attendance.']);
                            }
                            return response()->json(['message'=>'You cannot time out yet! Its too early in the morning.']);
                        }
                    }
                    else{
                        if($time_now->format("H:i:s") < '11:30:00'){
                            $grace_time = Carbon::parse($schedule->time_in)->addMinutes(21);
                            $logstatus = ($time_now->format("H:i:s") < $grace_time->format("H:i:s")) ? 'on-time' : 'late';
                            $update = new Attendance();
                            $update->employee_id = $request->employee_id;
                            $update->status_am = $logstatus;
                            $update->time_in_am = date("H:i:s");
                            $update->date = date("Y-m-d");

                            $update->save();
                            return response()->json(['message'=>'Success']);
                        }
                    }
                }
            }
            else{
                if($employee->count() > 0){
                    $attendance = Attendance::where('date', date("Y-m-d"))
                    ->where('employee_id', $request->employee_id)
                    ->first();

                    if($attendance->time_in_pm != null){
                        if(($time_now->format("H:i:s") > $schedule->time_out)){
                            $employee_id = Employee::all('id');
                            $attendance_id = Attendance::where('date', date("Y-m-d"))->get('employee_id');
                            $ctr = null;
                            for($x = 0; $x < $employee_id->count(); $x++)
                            {
                                $check_attendance = Attendance::where('date', date("Y-m-d"))
                                ->where('employee_id', $employee_id[$x]->id)
                                ->first('time_in_pm');
                                if($check_attendance == null){
                                   Attendance::where('employee_id', $employee_id[$x]->id)
                                    ->where('date', date("Y-m-d"))
                                    ->update(['status_pm' => 'absent']);
                                }
                            }

                            $attend = Attendance::where('employee_id', $request->employee_id)
                            ->get('time_in_pm');
                            if($attend != null){
                                Attendance::where('employee_id', $request->employee_id)
                                ->update(['time_out_pm' => date("H:i:s")]);
                                return response()->json(['message'=>'Success']);
                            }
                            else{
                                return response()->json(['message'=>'You cannot time out this Afternoon! you did not Time in before cut off. Notify HR']);
                            }
                        }
                        else{
                            $logstatus = ($time_now->format("H:i:s") < '13:21:00') ? 'on-time' : 'late';
                            $attendance_stats = Attendance::where('employee_id', $request->employee_id)
                            ->where('status_pm', 'on-leave')->first('status_pm');
                            if($attendance_stats->status_pm == 'on-leave'){
                                Attendance::where('employee_id', $request->employee_id)
                                ->where('date', date("Y-m-d"))
                                ->update(['status_pm' => $logstatus]);
                                return response()->json(['message'=>'Your Leave this day is void beacause you logged in Attendance.']);
                            }
                            return response()->json(['message'=>'You cannot time out yet! Its too early in the afternoon.']);
                        }
                    }
                    else{
                        if($time_now->format("H:i:s") < '15:45:00'){
                            $logstatus = ($time_now->format("H:i:s") < '13:21:00') ? 'on-time' : 'late';

                            $att = Attendance::where('date', date("Y-m-d"))->where('employee_id', $request->employee_id)
                            ->first();

                            if($att != null){
                                Attendance::where('date', date("Y-m-d"))->where('employee_id', $request->employee_id)
                                ->update([
                                    'status_pm' => $logstatus,
                                    'time_in_pm' => date("H:i:s"),
                                ]);
                                return response()->json(['message'=>'Success']);
                            }
                            else{
                                $update = new Attendance();
                                $update->employee_id = $request->employee_id;
                                $update->status_am = 'absent';
                                $update->status_pm = $logstatus;
                                $update->time_in_pm = date("H:i:s");
                                $update->date = date("Y-m-d");

                                $update->save();
                                return response()->json(['message'=>'Success']);
                            }
                        }
                    }
                }
            }
        }

    }

    public function update(request $request)
    {
        if($request->flag == 1){
            Attendance::where('employee_id', $request->id)
            ->where('date', $request->date)
            ->update(['status_am' => $request->status]);
            return response()->json(['message'=>'Successfully updated']);
        }
        else{
            Attendance::where('id', $request->id)
            ->where('date', $request->date)
            ->update(['status_pm' => $request->status]);
            return response()->json(['message'=>'Successfully updated']);
        }
    }

    public function get_Status()
    {
        $employees = Employee::with('attendances')->get();
        return $employees;
    }

    public function getDate(Request $request ,Attendance $attendance)
    {
        $today = today();
        $dates = [];
        $weeks = [];
        $months = [' ','January,', 'February,', 'March,', 'April,', 'May,', 'June,',
                  'July,', 'August,', 'September,', 'October,', 'November,', 'December,'
                  ];
        if($request->ctr3 == 3 && $request->ctr1 == 0 && $request->ctr2 == 0){
            for($i=1; $i < Carbon::createFromDate($request->year, $request->choosed_month, 1)->daysInMonth + 1; ++$i)
            {
                $dates[] = Carbon::createFromDate($request->year, $request->choosed_month, $i)->format('d');
                $weeks[] = strtoupper(Carbon::createFromDate($request->year, $request->choosed_month, $i)->isoFormat('ddd'));
                $days[] = Carbon::createFromDate($request->year, $request->choosed_month, $i)->format('Y-m-d');
            }
            return (['day'=>$dates, 'week'=>$weeks, 'days'=>$days, 'month'=>$months[$request->choosed_month], 'year'=>$request->year]);
        }
        else if($request->ctr1 == 1 && $request->ctr2 == 0){
            if($request->picked_year1 != null){
                $today->year = $request->picked_year1;
            }
            for($i=1; $i < Carbon::createFromDate($today->year, $request->before_month, 1)->daysInMonth + 1; ++$i)
            {
                $dates[] = Carbon::createFromDate($today->year, $request->before_month, $i)->format('d');
                $weeks[] = strtoupper(Carbon::createFromDate($today->year, $request->before_month, $i)->isoFormat('ddd'));
                $days[] = Carbon::createFromDate($today->year, $request->before_month, $i)->format('Y-m-d');
            }
            return (['day'=>$dates, 'week'=>$weeks, 'days'=>$days, 'month'=>$months[$request->before_month], 'year'=>$today->year]);
        }
        else if($request->ctr2 == 2 && $request->ctr1 == 0){

            if($request->picked_year2 != null){
                $today->year = $request->picked_year2;
            }
            for($i=1; $i < Carbon::createFromDate($today->year, $request->after_month, 1)->daysInMonth + 1; ++$i)
            {
                $dates[] = Carbon::createFromDate($today->year, $request->after_month, $i)->format('d');
                $weeks[] = strtoupper(Carbon::createFromDate($today->year, $request->after_month, $i)->isoFormat('ddd'));
                $days[] = Carbon::createFromDate($today->year, $request->after_month, $i)->format('Y-m-d');
            }
            return (['day'=>$dates, 'week'=>$weeks, 'days'=>$days, 'month'=>$months[$request->after_month], 'year'=>$today->year]);
        }
        else{
            for($i=1; $i < $today->daysInMonth + 1; ++$i)
            {
                $dates[] = Carbon::createFromDate($today->year, $today->month, $i)->format('d');
                $weeks[] = strtoupper(Carbon::createFromDate($today->year, $today->month, $i)->isoFormat('ddd'));
                $days[] = Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
            }
            return (['day'=>$dates, 'week'=>$weeks, 'days'=>$days, 'month'=>$months[$today->month], 'year'=>$today->year]);
        }
    }

    public function add(Request $request, Attendance $attendance)
    {
        $employee = Employee::where('id', $request->all())->first();
        $schedule = Schedule::where('id', $employee->schedule_id )->first();
        $time_in_check = Attendance::where('date', $request->date)->where('employee_id', $request->emp_id)->first();
        $time = new DateTime($request->time);
        $grace_time2 = Carbon::parse($schedule->time_in)->addMinutes(21);
        $time_in_am_deadline = '11:30:00';
        $time_in_pm_start = '12:30:00';
        $time_in_pm_deadline = '15:30:00';

        if($request->time < $time_in_am_deadline){
            $attendance = new Attendance();
            $attendance->employee_id = $request->emp_id;
            $attendance->date = $request->date;
            $attendance->time_in_am = $request->time;
            $status = ($time < $grace_time2) ? 'on-time': 'late';
            $attendance->status_am = $status;
            $attendance->save();
            return response()->json(['message'=>'Success']);
        }
        else if(($request->time > $time_in_am_deadline) && ($request->time < $time_in_pm_start)){
            Attendance::where('date', $request->date)->where('employee_id', $request->emp_id)
                ->update([
                    'time_out_am' => $request->time,
                ]);
                return response()->json(['message'=>'Success']);
        }
        else if(($request->time > $time_in_pm_start) && ($request->time < $time_in_pm_deadline)){
            $status = ($time < $grace_time2) ? 'on-time': 'late';
            Attendance::where('date', $request->date)->where('employee_id', $request->emp_id)
            ->update([
                'time_in_pm' => $request->time,
                'status_pm'  => $status
            ]);
            return response()->json(['message'=>'Success']);
        }
        else if($request->time > $schedule->time_out){
            Attendance::where('date', $request->date)->where('employee_id', $request->emp_id)
                ->update([
                    'time_out_pm' => $request->time,
                ]);
                return response()->json(['message'=>'Success']);
        }
        else if($request->time < $schedule->time_out && $time_in_check != null){
            return response()->json(['message'=>'Invalid time out input. The time you input is too early with for his schedule time out']);
        }
    }

    public function attendance_Total_per_Month(){
        $today = today();
        $total_for_late = 0;
        $total_for_absent = 0;
        $total_for_leave = 0;
        $total_lates = [];
        $total_absent = [];
        $total_leave = [];
        for($j = 1; $j<13;$j++){
            for($i=1; $i < Carbon::createFromDate($today->year, $j, 1)->daysInMonth + 1; ++$i)
            {
                $attendance_late_am = Attendance::where('status_am', 'late')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $attendance_late_pm = Attendance::where('status_pm', 'late')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $total_for_late += count($attendance_late_am) + count($attendance_late_pm);

                $attendance_absent_am = Attendance::where('status_am', 'absent')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $attendance_absent_pm = Attendance::where('status_pm', 'absent')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $total_for_absent += count($attendance_absent_am) + count($attendance_absent_pm);

                $attendance_leave_am = Attendance::where('status_am', 'on-leave')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $attendance_leave_pm = Attendance::where('status_pm', 'on-leave')
                ->where('date', Carbon::createFromDate($today->year, $j, $i)->format('Y-m-d'))
                ->get();
                $total_for_leave += count($attendance_leave_am) + count($attendance_leave_pm);
            }
            $total_lates[$j] = $total_for_late;
            $total_absent[$j] = $total_for_absent;
            $total_leave[$j] = $total_for_leave;
            $total_for_late = 0;
            $total_for_absent = 0;
            $total_for_leave = 0;
        }
        return (['late'=>$total_lates, 'absent'=>$total_absent, 'leave'=>$total_leave]);
    }

    public function destroy($id)
    {
        Attendance::where('employee_id', $id)->delete();
    }

}
