<?php

namespace App\Http\Controllers;

include 'Timezone.php';

use App\Attendance;
use App\Deduction;
use App\Employee;
use App\Payroll;
use App\Payslip;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
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
        $request->validate([
            'start_date'=> ['required', 'date'],
            'end_date'  => ['required', 'date']
        ]);

        $payroll = Payroll::create($request->all());
        return response()->json(['message'=>'success','data'=>$payroll]);
    }

    public function show(Payroll $payroll)
    {
        $payroll = Payroll::all();
        for($x = 0; $x < count($payroll); $x++)
        {                
            $id = $payroll[$x]->id;
            $payslip = Payslip::where('payroll_id', $id)->get();
            $total_gross = 0;
            for($y = 0; $y < count($payslip); $y++)
            {
                $total_gross = $total_gross + $payslip[$y]->gross_salary; 
            }
            $payroll[$x]->total_gross = round($total_gross, 2);
            $payroll[$x]->total_employee = count($payslip);
        }
        return $payroll;
    }
   
    public function edit(Payroll $payroll)
    {
        //
    }
   
    public function update(Request $request, Payroll $payroll,$id)
    {
        Payroll::where('id', $id)->update($request->all());
    }
   
    public function destroy(Request $request)
    {
        $id = $request->id;
        $date = new DateTime($request->updated_at);
        $dateTime = $date->format('Y-m-d');
        $deduc_id = Deduction::where('deduction_name', 'cash-advance')->get()->first()->id;

        Payroll::destroy($id);
        DB::table('deduction_employee')->where([['date_deducted', 'like', '%'.$dateTime.'%'],['deduction_id', '!=', $deduc_id]])->delete();
        return response()->json(['message' => 'payroll deleted successfully']);
    }

    public function currentMonthDate()
    {
        $month_start= Carbon::now()->startOfMonth()->format('Y-m-d');
        $month_end  = Carbon::now()->endOfMonth()->format('Y-m-d');
        return
        [
            'start_date'    => $month_start,
            'end_date'      => $month_end,
        ];
    }

    public function computation(Request $request)
    {
        // Daily Rate = (Monthly Rate x (Number of months in a year which is 12)) / Total working days in a year
        // We have 52 weeks in a year. If your employees are required to work from Mondays to Fridays (5 days a week),
        // just multiply 5 by 52 which is 260 but during leap years we sometimes get additional 1 working day so some companies actually use 261.

        // Example
        // 459.77 = (10,000 x 12) / 260
        // 459.77 = 120,000 / 260
        // 459.77 = Daily Rate


        // COVERAGE OF DATES IN STRING
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
       
        // COUNT TOTAL NUMBER DAYS IN A MONTH (specified date)
        $pass_date = strtotime($start_date);
        $total_days = cal_days_in_month(CAL_GREGORIAN, date('m', $pass_date), date('Y', $pass_date));
        // $diff_start_date = Carbon::createFromFormat('Y-m-d', $request->start_date);  
        // $weekday = $diff_start_date->daysInMonth;

        // COUNT SATURDAYS IN A MONTH (specified date)
        $total_saturdays = 0;
        for($i = 1; $i <= $total_days; $i++)
        {
            if(date('N',strtotime(date('Y', $pass_date).'-'.date('m', $pass_date).'-'.$i)) == 6)
            {
                $total_saturdays++;
            }
        }

        // COUNT WEEKDAYS IN A MONTH (specified date)
        $total_weekdays = 0;
        for($i = 1; $i <= $total_days; $i++)
        {
            $date = date('Y', $pass_date).'/'.date('m', $pass_date).'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            //if not a weekend add day to array
            if($day_name != 'Sun' && $day_name != 'Sat'){
                $total_weekdays++;
            }
        }

        // COUNT DAYS OF CURRENT MONTH
        // $current_days = date('t');

        // COUNT WEEKENDS (SAT & SUN)
        $diff_start_date = Carbon::createFromFormat('Y-m-d', $start_date);
        $diff_end_date = Carbon::createFromFormat('Y-m-d', $end_date);
        // $diff_weekends = $diff_start_date->diffInDaysFiltered(function(Carbon $date) {
        //     return $date->isWeekend();
        // }, $diff_end_date);

        // COUNT WEEKDAYS (MON - FRI)
        // $diff_weekdays = $diff_start_date->diffInDaysFiltered(function(Carbon $date) {
        //     return $date->isWeekday();
        // }, $diff_end_date);

        // CHECK IF THERE IS EMPLOYEE
        // $checkEmployee = Attendance::with('employee')->where('date', '>=', $start_date)->where('date', '<=', $end_date)->count();
        $checkEmployee = Attendance::with('employee')->whereBetween('date', [$start_date, $end_date])->count();
        if($checkEmployee != 0)
        {
            // $checkPayroll = Payroll::where('start_date', $start_date)->where('end_date', $end_date)->count();
            $checkPayroll = Payroll::where([['start_date', $start_date],['end_date', $end_date]])->count();
            if($checkPayroll == 0)
            {
                Payroll::create([
                    'start_date'    => $start_date,
                    'end_date'      => $end_date,
                ]);
                $id = Payroll::latest('id')->get()->first()->id;
            }
            else
            {
                $id = Payroll::where([['start_date', $start_date],['end_date', $end_date]])->get('id')->first()->id;
                Payroll::where('id', $id)->update($request->all());
            }
                
            // GET ALL EMPLOYEES WITH POSITIONS
            $emp_pos = Employee::with(['position', 'rate'])->get();

            for($x = 0; $x < count($emp_pos); $x++)
            {                
                $emp_id = $emp_pos[$x]->id;
                // $emp_id = 6;

                $basic_salary = $emp_pos[$x]->rate->basic_salary;
                $additional = $emp_pos[$x]->rate->additional;
                $new_salary = round(($basic_salary + $additional), 2);

                // FOR DAILY
                if($emp_pos[$x]->rate->rate_type == 'Daily')
                {
                    $daily_salary = $new_salary;
                }
                // FOR MONTHLY
                else
                {
                    $daily_salary = ($new_salary * 12) / 260;
                }
                
                // $late_am = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_am', 'late')->count();
                $attendance = Attendance::where('employee_id', $emp_id)->whereBetween('date', [$start_date, $end_date])->get();

                // COUNT LATE
                $late_am    = $attendance->where('status_am', 'late')->count();
                $late_pm    = $attendance->where('status_pm', 'late')->count();
                $total_late = $late_am + $late_pm;

                // COUNT ABSENT
                $absent_am  = $attendance->where('status_am', 'absent')->count();
                $absent_pm  = $attendance->where('status_pm', 'absent')->count();
                $total_absent = $absent_am + $absent_pm;

                // COUNT ON-TIME
                $time_am    = $attendance->where('status_am', 'on-time')->count();
                $time_pm    = $attendance->where('status_pm', 'on-time')->count();
                $total_time = $time_am + $time_pm;

                // COUNT ON-LEAVE
                $leave_am   = $attendance->where('status_am', 'on-leave')->count();
                $leave_pm   = $attendance->where('status_pm', 'on-leave')->count();
                $total_leave = $leave_am + $leave_pm;

                $total = $total_late + $total_absent + $total_time + $total_leave;

                if($total != 0)
                {
                    $saturdays_get = array();
                    $saturday_salary = 0;

                    while ($diff_start_date < $diff_end_date) {
                        if ($diff_start_date->format('w') == 6) {
                            $sat_date = $diff_start_date->format('Y-m-d');
                            $saturday_attendance = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->get();

                            $sat_late_am    = $saturday_attendance->where('status_am', 'late')->count();
                            $sat_late_pm    = $saturday_attendance->where('status_pm', 'late')->count();
                            $total_sat_late = $sat_late_am + $sat_late_pm;

                            $sat_absent_am  = $saturday_attendance->where('status_am', 'absent')->count();
                            $sat_absent_pm  = $saturday_attendance->where('status_pm', 'absent')->count();
                            $total_sat_absent = $sat_absent_am + $sat_absent_pm;

                            $sat_time_am    = $saturday_attendance->where('status_am', 'on-time')->count();
                            $sat_time_pm    = $saturday_attendance->where('status_pm', 'on-time')->count();
                            $total_sat_time = $sat_time_am + $sat_time_pm;

                            $sat_leave_am   = $saturday_attendance->where('status_am', 'on-leave')->count();
                            $sat_leave_pm   = $saturday_attendance->where('status_pm', 'on-leave')->count();
                            $total_sat_leave = $sat_leave_am + $sat_leave_pm;

                            $total_sat = $total_sat_late + $total_sat_absent + $total_sat_time + $total_sat_leave;

                            if($total_sat != 0)
                            {
                                $saturdays_get[] = $diff_start_date->format('Y-m-d');
                            }
                        }
                        $diff_start_date->modify('+1 day');
                    }

                    if($total_sat != 0)
                    {
                        $saturday_salary = ($saturday_salary + ((count($saturdays_get) - ($total_sat_absent / 2)) * (((20 / 100) * $new_salary) / $total_saturdays)));
                    }

                    // CONVERT OUR PERCENTAGE VALUE (TOTAL LATE) INTO A DECIMAL
                    $lateInDecimal = $total_late / 100;

                    // $days_not_worked = (count($saturdays_get) + $diff_weekdays) - ($total / 2);

                    // GET CASH ADVANCE
                    $deduc_id = Deduction::where('deduction_name', 'cash-advance')->get()->first()->id;
                    // $cash = DB::table('deduction_employee')->where('employee_id', $emp_id)->whereBetween('date_deducted', [$start_date.' 00:00', $end_date.' 23:59'])->where('deduction_id', $deduc_id)->get();
                    $cash = DB::table('deduction_employee')->where([['employee_id', $emp_id],['deduction_id', $deduc_id]])->whereBetween('date_deducted', [$start_date.' 00:00', $end_date.' 23:59'])->get();
                    $new = [];
                    $deductions = count($cash);
                    if($deductions != 0)
                    {
                        for($i=0; $i < $deductions; $i++)
                        {
                            $new[]= $cash[$i]->amount;
                        }
                    }

                    // COMPUTE DEDUCTIONS
                    $deduction_late = $lateInDecimal * ($new_salary / 2); // 360 // 36
                    $deduction_absent = $total_absent * ($daily_salary / 2); // 400
                    $deduction_cash = array_sum($new);
                    $total_deduction = round(($deduction_late + $deduction_absent + $deduction_cash), 2);

                    // COMPUTE GROSS SALARY
                    // $sub_total_salary = round(($daily_salary * ($diff_weekends + (($total - $total_sat) / 2 ))), 2);
                    $sub_total_salary = round(($daily_salary * (($total - $total_sat) / 2 )), 2);
                    $total_salary = $sub_total_salary + $saturday_salary;

                    // COMPUTE NET SALARY
                    $net_salary = $total_salary - $total_deduction;

                    // $checkPayslip = Payslip::where('payroll_id', $id)->where('employee_id', $emp_id)->count();
                    $checkPayslip = Payslip::where([['payroll_id', $id],['employee_id', $emp_id]])->count();
                    if(($checkPayroll == 0) || ($checkPayslip == 0))
                    {
                        Payslip::create([
                            'payroll_id'        => $id,
                            'employee_id'       => $emp_id,
    
                            'total_weekdays'    => $total_weekdays,
                            'counts_late'       => ($total_late / 2),
                            // 'days_absent'       => ($days_not_worked + ($total_absent / 2)),
                            'days_absent'       => ($total_absent / 2),
                            'days_leave'        => ($total_leave / 2),
                            'days_worked'       => (($total / 2) - ($total_absent / 2) - count($saturdays_get) - ($total_sat_absent / 2)),
                            'days_saturday'     => (count($saturdays_get) - ($total_sat_absent / 2)),
                            // 'days_saturday'     => $total_sat_absent,
    
                            'basic_salary'      => $basic_salary,
                            'additional'        => $additional,
                            'leave_pay'         => ($daily_salary * ($total_leave / 2)),
                            'saturday_pay'      => $saturday_salary,
                            // 'deduc_late'        => $deduction_late,
                            // 'deduc_absent'      => $deduction_absent,
    
                            'gross_salary'      => $total_salary,
                            'total_deduction'   => $total_deduction,
                            'net_salary'        => $net_salary,
                        ]);

                        $latestPayslip = Payslip::latest('id')->first();
                        $deduction = Deduction::all();
                        $employee = Employee::find($emp_id);
                        for($d = 0; $d < count($deduction); $d++)
                        {
                            if($deduction[$d]->deduction_name == 'late')
                            {
                                if($deduction_late != 0)
                                {
                                    $deduction_id = $deduction[$d]->id;
                                    $employee->deductions()->attach($deduction_id, ['amount' => $deduction_late, 'date_deducted' => $latestPayslip->updated_at]);
                                }
                            }
                            if($deduction[$d]->deduction_name == 'absent')
                            {
                                if($deduction_absent != 0)
                                {
                                    $deduction_id = $deduction[$d]->id;
                                    $employee->deductions()->attach($deduction_id, ['amount' => $deduction_absent, 'date_deducted' => $latestPayslip->updated_at]);
                                }
                            }
                        }
                    }
                    else
                    {
                        // $date = Payslip::where('payroll_id', $id)->where('employee_id', $emp_id)->latest()->get()->first()->updated_at;
                        $date = Payslip::where([['payroll_id', $id],['employee_id', $emp_id]])->latest()->get()->first()->updated_at;
                        $dateTime = $date->format('Y-m-d H:i:s');

                        // DB::table('deduction_employee')->where('date_deducted', 'like', '%'.$dateTime.'%')->where('employee_id', $emp_id)->where('deduction_id', '!=', $deduc_id)->delete();
                        DB::table('deduction_employee')->where('date_deducted', $dateTime)->where('employee_id', $emp_id)->where('deduction_id', '!=', $deduc_id)->delete();
                        
                        Payslip::where('payroll_id', $id)->where('employee_id', $emp_id)->update([
                            'total_weekdays'    => $total_weekdays,
                            'counts_late'       => ($total_late / 2),
                            'days_absent'       => ($total_absent / 2),
                            'days_leave'        => ($total_leave / 2),
                            'days_worked'       => (($total / 2) - ($total_absent / 2) - count($saturdays_get) - ($total_sat_absent / 2)),
                            'days_saturday'     => (count($saturdays_get) - ($total_sat_absent / 2)),
                            
                            'basic_salary'      => $basic_salary,
                            'additional'        => $additional,
                            'leave_pay'         => ($daily_salary * ($total_leave / 2)),
                            'saturday_pay'      => $saturday_salary,
                            
                            'gross_salary'      => $total_salary,
                            'total_deduction'   => $total_deduction,
                            'net_salary'        => $net_salary,
                        ]);

                        $upDate = Payslip::latest('updated_at')->get()->first()->updated_at;
                        $deduction = Deduction::all();
                        $employee = Employee::find($emp_id);
                        for($d = 0; $d < count($deduction); $d++)
                        {
                            if($deduction[$d]->deduction_name == 'late')
                            {
                                if($deduction_late != 0)
                                {
                                    $deduction_id = $deduction[$d]->id;
                                    $employee->deductions()->attach($deduction_id, ['amount' => $deduction_late, 'date_deducted' => $upDate]);
                                }
                            }
                            if($deduction[$d]->deduction_name == 'absent')
                            {
                                if($deduction_absent != 0)
                                {
                                    $deduction_id = $deduction[$d]->id;
                                    $employee->deductions()->attach($deduction_id, ['amount' => $deduction_absent, 'date_deducted' => $upDate]);
                                }
                            }
                        }
                    }
                }
            }
            return response()->json(['message' => 'payroll added successfully']);
        }
        return response()->json(['message' => 'empty']);
    }
    



    // BACKUP
    public function computation_backup(Request $request)
    {
        // Daily Rate = (Monthly Rate x (Number of months in a year which is 12)) / Total working days in a year
        // We have 52 weeks in a year. If your employees are required to work from Mondays to Fridays (5 days a week),
        // just multiply 5 by 52 which is 260 but during leap years we sometimes get additional 1 working day so some companies actually use 261.

        // Example
        // 459.77 = (10,000 x 12) / 261
        // 459.77 = 120,000 / 261
        // 459.77 = Daily Rate

        // COVERAGE OF DATES IN STRING
        $start_date = $request->start_date;
        $end_date   = $request->end_date;

        // COUNT TOTAL NUMBER DAYS IN A MONTH (specified date)
        $pass_date  = strtotime($start_date);
        $total_days = cal_days_in_month(CAL_GREGORIAN, date('m', $pass_date), date('Y', $pass_date));
        // $diff_start_date = Carbon::createFromFormat('Y-m-d', $request->start_date);  
        // $weekday = $diff_start_date->daysInMonth;

        // COUNT TOTAL NUMBER SATURDAYS IN A MONTH (specified date)
        $total_saturdays = 0;
        for($i = 1; $i <= $total_days; $i++)
        {
            if(date('N',strtotime(date('Y', $pass_date).'-'.date('m', $pass_date).'-'.$i)) == 6)
            {
                $total_saturdays++;
            }
        }

        $total_weekdays = 0;
        for($i = 1; $i <= $total_days; $i++)
        {
            $date = date('Y', $pass_date).'/'.date('m', $pass_date).'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            //if not a weekend add day to array
            if($day_name != 'Sun' && $day_name != 'Sat'){
                $total_weekdays++;
            }
        }

        // COUNT DAYS OF CURRENT MONTH
        // $current_days = date('t');

        // COUNT WEEKENDS (SAT & SUN)
        $diff_start_date = Carbon::createFromFormat('Y-m-d', $start_date);
        $diff_end_date = Carbon::createFromFormat('Y-m-d', $end_date);
        $diff_weekends = $diff_start_date->diffInDaysFiltered(function(Carbon $date) {
            return $date->isWeekend();
        }, $diff_end_date);

        // GET ALL EMPLOYEES WITH POSITIONS
        $emp_pos = Employee::with('position')->get();

        for($x = 0; $x < count($emp_pos); $x++)
        {
            $emp_id = $emp_pos[$x]->id;
            // $emp_id = 6;

            $basic_salary = $emp_pos[$x]->position->basic_salary;
            $additional = $emp_pos[$x]->additional;
            // $basic_salary = 6000;
            // $additional = 0;
            $new_salary = round(($basic_salary + $additional), 2);

            $daily_salary = ($new_salary / $total_weekdays);

            // COUNT LATE
            $late_am = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_am', 'late')->count();
            $late_pm = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_pm', 'late')->count();
            $total_late = $late_am + $late_pm;

            // CONVERT OUR PERCENTAGE VALUE (TOTAL LATE) INTO A DECIMAL
            // $lateInDecimal = $total_late / 100;

            // COUNT ABSENT
            $absent_am = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_am', 'absent')->count();
            $absent_pm = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_pm', 'absent')->count();
            $total_absent = $absent_am + $absent_pm;

            // COUNT ON-TIME
            $time_am = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_am', 'on-time')->count();
            $time_pm = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_pm', 'on-time')->count();
            $total_time = $time_am + $time_pm;

            // COUNT ON-LEAVE
            $leave_am = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_am', 'on-leave')->count();
            $leave_pm = Attendance::where('employee_id', $emp_id)->where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('status_pm', 'on-leave')->count();
            $total_leave = $leave_am + $leave_pm;

            $total = $total_late + $total_absent + $total_time + $total_leave;

            if($total != 0)
            {
                $saturdays_get = array();
                $saturday_salary = 0;
                while ($diff_start_date < $diff_end_date) {
                    if ($diff_start_date->format('w') == 6) {
                        $sat_date = $diff_start_date->format('Y-m-d');
                        
                        $sat_late_am = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_am', 'late')->count();
                        $sat_late_pm = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_pm', 'late')->count();
                        $total_sat_late = $sat_late_am + $sat_late_pm;

                        $sat_absent_am = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_am', 'absent')->count();
                        $sat_absent_pm = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_pm', 'absent')->count();
                        $total_sat_absent = $sat_absent_am + $sat_absent_pm;

                        $sat_time_am = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_am', 'on-time')->count();
                        $sat_time_pm = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_pm', 'on-time')->count();
                        $total_sat_time = $sat_time_am + $sat_time_pm;

                        $sat_leave_am = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_am', 'on-leave')->count();
                        $sat_leave_pm = Attendance::where('employee_id', $emp_id)->where('date', $sat_date)->where('status_pm', 'on-leave')->count();
                        $total_sat_leave = $sat_leave_am + $sat_leave_pm;

                        $total_sat = $total_sat_late + $total_sat_absent + $total_sat_time + $total_sat_leave;

                        if($total_sat != 0)
                        {
                            $saturdays_get[] = $diff_start_date->format('Y-m-d');

                        }
                    }
                    $diff_start_date->modify('+1 day');
                }
                if($total_sat != 0)
                {
                    $saturday_salary = ($saturday_salary + (count($saturdays_get) * (((20 / 100) * $new_salary) / $total_saturdays)));
                }

                // CONVERT OUR PERCENTAGE VALUE (TOTAL LATE) INTO A DECIMAL
                $lateInDecimal = $total_late / 100;

                // COMPUTE DEDUCTIONS
                $deduction_late = $lateInDecimal * ($new_salary / 2); // 360 // 36
                $deduction_absent = $total_absent * ($daily_salary / 2); // 400
                $total_deduction = round(($deduction_late + $deduction_absent), 2);

                // COMPUTE GROSS SALARY
                // $sub_total_salary = round(($daily_salary * ($diff_weekends + (($total - $total_sat) / 2 ))), 2);
                $sub_total_salary = round(($daily_salary * (($total - $total_sat) / 2 )), 2);
                $total_salary = $sub_total_salary + $saturday_salary;

                // COMPUTE NET SALARY
                $net_salary = $total_salary - $total_deduction;

                Payroll::create([
                    'employee_id'   => $emp_id,
                    'basic_salary'  => $total_salary,
                    'deduction'     => $total_deduction,
                    'net_salary'    => $net_salary,
                    'start_date'    => $start_date,
                    'end_date'      => $end_date
                ]);
            }
        }
        return response()->json(['message' => 'payroll added successfully']);
    }
}