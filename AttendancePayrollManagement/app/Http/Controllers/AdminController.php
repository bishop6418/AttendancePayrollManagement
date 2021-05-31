<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Benefit;
use App\Employee;
use App\Leave;
use App\Payroll;
use App\Payslip;
use App\Position;
use App\User;
use Attribute;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;


class AdminController extends Controller
{
    public function login(request $request){

        $this->validate($request, [
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required',
          ]);

        $user= User::where('email',$request->email)->first();
        if (Auth::attempt($request->only('email','password')))
        {
            $token = $user->createToken('authToken')->accessToken;

            if($user->hasRole('admin'))
            {
                return [
                    'user' => $user,
                    'access_token' => $token,
                    'name' => $user->name,
                    'role'=>'admin'
                  ];
            }
            if($user->hasRole('user'))
            {
                return [
                    'user' => $user,
                    'access_token' => $token,
                    'name' => $user->name,
                    'role'=>'user'
                  ];
            }
        }
        throw ValidationException::withMessages([
            'email'=>['Credentials are incorrect']
        ]);
    }

    public function register(Request $request)
    {
          $this->validate($request, [
                'first_name'=> 'required',
                'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',

              ]);
        $user = User::where('email',$request->email)->first();

        if(isset($user->id))
            {
                return response()->json(['error'=>'exist']);
                // return response()->json(['error'=>'email already exists.'], 401);
            }
        $user= new User();
        $user->name=$request->first_name;
        $user->email=$request->email;
        // $user->password=bcrypt($request->password);
        $user->password='$2y$10$Iy2N2bUpmuo61kaHKMPEKuD966cguK0a2LaMo1k0D1LKf.x2rIk6i';//Qonvex123
        $user->save();
        $token = $user->createToken('authToken')->accessToken;
        $user->assignRole('user');
        return response()->json(['access_token'=>$token, 'user'=>$user],200);
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens->each(function ($token, $key) {
                 $token->delete();
             });
          }
    }

    public function currentUser()
    {
        return Auth::user();
    }

    public function isAdmin()
    {
        $user = Auth::user();
       if( $user->hasRole('user'))
       {
           return ('true');
       }
       else
       {
           return false;
       }

    }

    public function change_password(Request $request)
    {
        // dd($request);
        // // $request->validate([
        // //     'current_password' => ['required'],
        // //     'new_password' => ['required'],
        // //     'new_confirm_password' => ['same:new_password'],
        // // ]);
        // $old_password = Auth::user()->password;
        // if (Hash::check($request->old_password, $old_password)) {
        //     $userId = Auth::user()->id;
        //     $user = User::find($userId);
        //     $user->password = Hash::make($request->new_password);
        //     $user->save();
        //     return back()->with('message', 'passsword cvhange');
        // }
        //     else
        //     {
        //         return back()->withErrors(['sorry']);
        //     }
    }

    // user view information
    public function getInfo()
    {
        $auth = Auth::user()->email;
        $employee = Employee::where('email',$auth)->first();
        $position = Position::where('id',$employee->position_id)->first();
        $benefit = Employee::with('benefits')->find($employee->id);
        $benefit_refnumber = $benefit->benefits[0]->pivot->ref_number;
        $benefit_name = $benefit->benefits[0]->name;
        return response()->json(['employee'=>$employee, 'benefit_name'=> $benefit_name, 'ref_number'=>$benefit_refnumber, 'position'=> $position]);
        // $auth = Auth::user()->email;
        // $employee = Employee::with('position')->with('benefits')->where('email',$auth)->get();
        // return $employee;
    }

        //user request leave
    public function requestLeave(Request $request)
    {
        $employee = Employee::where('email',Auth::user()->email)->get('id');
        $user = Employee::find($employee[0]->id);
        $from = new DateTime($request->from);
        $to = new DateTime($request->to);
        $interval = $from->diff($to);
        $no_of_days = $interval->format('%a');

        if($user->leave_credits>=($no_of_days+1)){
            $user->leaves()->create(
             [
                 'from' => $request->from,
                 'to'=> $request->to,
                 'no_of_days' => $no_of_days+1,
                 'description' => $request->description,
                 'status'=>'Pending'
             ]
         );
         }
         else{
             return response()->json(['message' => 'empty']);
         }
    }

        //admin request leave
    public function admin_requestLeave(Request $request)
    {
        $user = Employee::find($request->employee['id']);
        $from = new DateTime($request->from);
        $to = new DateTime($request->to);
        $leave = Carbon::createFromDate($request->from);
        // dd($leave);
        $interval = $from->diff($to);
        $no_of_days = $interval->format('%a');

        if($request->day!='whole-day')
        {
            $no_leave = ($no_of_days+1)/2;
        }
        else
        {
            $no_leave = $no_of_days+1;
        }

        if($user->leave_credits>=($no_leave)){
           $user->leaves()->create(
            [
                'from' => $request->from,
                'to'=> $request->to,
                'no_of_days' => $no_leave,
                'description' => $request->description,
                'status'=> $request->status
            ]
        );
            if($request->status == "Approved")
            {
                $user->update([
                    'leave_credits'=>$user->leave_credits - ($no_leave)
                ]);

                    $attendance = new Attendance();
                    $attendance->employee_id = $request->employee['id'];
                    $attendance->date = $leave->format('Y-m-d');
                    if($request->day == 'am')
                    {
                        $attendance->status_am = 'on-leave';
                        $attendance->status_pm = '';
                    }
                    else if ($request->day == 'pm')
                    {
                        $attendance->status_am = '';
                        $attendance->status_pm = 'on-leave';
                    }
                    else
                    {
                        $attendance->status_am = 'on-leave';
                        $attendance->status_pm = 'on-leave';
                    }
                    $attendance->save();

                    for($i=1;$i<=$no_of_days;$i++)
                    {
                        $attendance = new Attendance();
                        $attendance->employee_id = $request->employee['id'];
                        $attendance->date = $leave->addDay();
                        if($request->day == 'am')
                        {
                            $attendance->status_am = 'on-leave';
                            $attendance->status_pm = '';
                        }
                        else if ($request->day == 'pm')
                        {
                            $attendance->status_am = '';
                            $attendance->status_pm = 'on-leave';
                        }
                        else
                        {
                            $attendance->status_am = 'on-leave';
                            $attendance->status_pm = 'on-leave';
                        }
                        $attendance->save();
                    }
                // $attendance = new Attendance();
                // $attendance->employee_id = $request->employee['id'];
                // $attendance->status_am = 'on-leave';
                // $attendance->status_pm = 'on-leave';
                // $attendance->date = $leave->format('Y-m-d');
                // $attendance->save();
                // for($i=1;$i<=$no_of_days;$i++)
                // {
                //     $attendance = new Attendance();
                //     $attendance->employee_id = $request->employee['id'];
                //     $attendance->status_am = 'on-leave';
                //     $attendance->status_pm = 'on-leave';
                //     $attendance->date = $leave->addDay();
                //     $attendance->save();
                // }

            }
        }
        else{
            return response()->json(['message' => 'empty']);
        }

    }

        //show user leaves
    public function getLeaves()
    {
        $employee = Employee::where('email',Auth::user()->email)->first();
        $leave = Leave::where('employee_id',$employee->id)->get();
        return ['leave'=>$leave, 'employee'=>$employee];
    }

        //admin delete leave
    public function deleteLeave($id){
        $leave = Leave::find($id);
        $leave->delete();
    }

        //admin edit leave
    public function editLeave(Request $request){
        // dd($request);
            // $user = Leave::find($request->id);

            // $from = new DateTime($request->from);
            // $to = new DateTime($request->to);
            // $interval = $from->diff($to);
            // $no_of_days = $interval->format('%a');

            // Leave::where('id',$request->id)->update(
            //     [
            //         'employee_id' => $request->employee_id,
            //         'from' => $request->from,
            //         'to'=> $request->to,
            //         'no_of_days' => $no_of_days+1,
            //         'description' => $request->description,
            //         'status'=> $request->status
            //     ]
            //     );
            $user = Employee::find($request->employee['id']);;
            $leave = Leave::find($request->id);
            $from = new DateTime($request->from);
            $to = new DateTime($request->to);
            $leave_date = Carbon::createFromDate($request->from);
            $interval = $from->diff($to);
            $no_of_days = $interval->format('%a');

            if($user->leave_credits>=($no_of_days+1)){
               $leave->update(
                [
                    'from' => $request->from,
                    'to'=> $request->to,
                    'no_of_days' => $no_of_days+1,
                    'description' => $request->description,
                    'status'=> $request->status
                ]
            );
                if($request->status == "Approved")
                {
                    $user->update([
                        'leave_credits'=>$user->leave_credits - ($no_of_days+1)
                    ]);
                    $attendance = new Attendance();
                    $attendance->employee_id = $request->employee['id'];
                    $attendance->status_am = 'on-leave';
                    $attendance->status_pm = 'on-leave';
                    $attendance->date = $leave_date->format('Y-m-d');
                    $attendance->save();
                    for($i=1;$i<=$no_of_days;$i++)
                    {
                        $attendance = new Attendance();
                        $attendance->employee_id = $request->employee['id'];
                        $attendance->status_am = 'on-leave';
                        $attendance->status_pm = 'on-leave';
                        $attendance->date = $leave_date->addDay();
                        $attendance->save();
                    }

                }
            }
            else{
                return response()->json(['message' => 'empty']);
            }
    }

        //admin show leave
    public function admin_leaves()
    {
        $employee = Leave::with('employee')->get();
        return $employee;
    }

    // public function current_month()
    // {
    //     $month_start = Carbon::now()->startOfMonth()->format('Y-m-d');
    //     $month_end = Carbon::now()->endOfMonth()->format('Y-m-d');
    //     return ['start_date' => $month_start, 'end_date'=> $month_end];
    //     // return $month_start, $mo;


    // }
    public function my_payroll()
    {
        $employee = Employee::where('email',Auth::user()->email)->first('id');
        $payroll = Payslip::with('payroll')->where('employee_id',$employee->id)->get();
        return $payroll;

    }

    public function qoutes_of_the_day(){
        $myfile = fopen("AspiringQoutes.txt", "r") or die("Unable to open file!");
        $qoutes = [];
        $i = 0;
        if($myfile){
            while(($qoutes[$i] = fgets($myfile)) !== false)
            {
                $i++;
            }
        }
        else
        {
            die("Unable to open file!");
        }
        fclose($myfile);
        return $qoutes;
    }

}
