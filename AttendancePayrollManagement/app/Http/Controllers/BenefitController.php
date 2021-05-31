<?php

namespace App\Http\Controllers;

use App\Benefit;
use App\Compensation;
use App\Deduction;
use App\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BenefitController extends Controller
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
            'name' => ['required']
        ]);

        Benefit::create($request->all());
        return response()->json(['message' => 'benefit added successfully']);
    }

    
    public function show(Benefit $benefit)
    {
    //     $q = Employee::with('attendances')->with('position')->with('deductions')->with('schedule')->where('id', 6)->get();
    //    return $q;
    // dd('$test');
        return Benefit::all();
        // $users = User::role('user')->get();
        // return $users;
        // $count = DB::table('deduction_employee')->where('deduction_id',3)->count();
        // dd($count);
        // for($i=0;$i<$count;$i++)
        // {

        // }
        // $emp_pos = Employee::with('position')->get();


        // $deductions = DB::table('deduction_employee')->where('employee_id',7)->whereBetween('date_deducted',['2021-03-01','2021-03-31'])->where('deduction_id',3)->count(); //4 counts
        // $new = [];
        // $cash = DB::table('deduction_employee')->where('employee_id',7)->whereBetween('date_deducted',['2021-03-01','2021-03-31'])->where('deduction_id',3)->get();
        // if($deductions!=0)
        // {
        //     for($i=0;$i < $deductions;$i++)
        //    {
        //     $new[]= $cash[$i]->amount;
        //    }
        //    $total= array_sum($new);
        //    return $total;
        // }
        
    }

  
    public function edit(Benefit $benefit)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        Benefit::where('id', $id)->update($request->all());
        return response()->json(['message' => 'benefit updated successfully']);
    }

   
    public function destroy($id)
    {
        Benefit::destroy($id);
        return response()->json(['message' => 'benefit deleted successfully']);
    }

    public function destroyPivot(Request $request)
    {
        DB::table('benefit_employee')->where('benefit_id', $request->benefit_id)->where('employee_id', $request->employee_id)->delete();
        return response()->json(['message' => "employee's benefit deleted successfully"]);
    }

    public function pluck(Request $request)
    {
        $employee = Employee::latest('id')->first();
        $benefit = Benefit::find($request->id);
        $employee->benefits()->attach($benefit, ['ref_number' => $request->ref_number]);
    }

    public function pluckAdd(Request $request)
    {
        // dd($request[1]['id']);
        $errors = array();
        for($x = 0; $x < count($request->all()); $x++)
        {
            $benefit_id = $request[$x]['id'];
            $employee_id = $request[$x]['employee_id'];
            // dd($benefit_id, $employee_id);

            if ($benefit_id != null)
            {
                $employee = Employee::find($employee_id);
                $benefit = Benefit::find($benefit_id);
                $benefit_employee = DB::table('benefit_employee')->where('benefit_id', $benefit_id)->where('employee_id', $employee_id)->get();
                if (count($benefit_employee) == 0)
                {
                    $employee->benefits()->attach($benefit, ['ref_number' => $request[$x]['ref_number']]);        
                }
                else
                {
                    // $errors[] = response()->json(['exist' => $benefit->name]);
                    $errors[] = $benefit->name;
                }
            }
        }
        // return response()->json(['employee'=>$employee, 'benefit_name'=> $benefit_name, 'ref_number'=>$benefit_refnumber, 'position'=> $position]);
        return $errors;
        
        
        // dd($benefit_employee[0]);
        // for($x = 0; $x < count($benefit_employee); $x++)
        // {
        //     $check = $benefit_employee[$x]->where('benefit_id', $benefit_id)->where('employee_id', $employee_id)->count();
        //     if ($check == 0)
        //     {
        //         $employee = Employee::find($employee_id);
        //         $benefit = Benefit::find($benefit_id);
        //         $employee->benefits()->attach($benefit, ['ref_number' => $request->ref_number]);        
        //     }
        //     else
        //     {
        //         return response()->json(['error' => 'exist']);
        //     }
        // }
    }

    public function pluckUpdate(Request $request, $id)
    {
        $employee = Employee::find($id);
        $benefit = Benefit::find($request->id);
        $employee->benefits()->updateExistingPivot($benefit, ['ref_number' => $request->ref_number]);
    }
}
