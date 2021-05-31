<?php

namespace App\Http\Controllers;

use App\Deduction;
use App\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

include 'Timezone.php';

class DeductionController extends Controller
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
        $deduction = new Deduction();

        $deduction->deduction_name = $request->deduction_name;
        $deduction->description = $request->description;
        $deduction->value = $request->value;
        $deduction->value_type = $request->value_type;
       
        $deduction->save();
        return response()->json(['message'=>'success','data'=>$deduction]);
    }

    public function show(Deduction $deduction)
    {
        return Deduction::all();
    }

  
    public function edit(Deduction $deduction)
    {
        //
    }

    
    public function update(Request $request, Deduction $deduction, $id)
    {
        Deduction::where('id', $id)->update($request->all());
    }


    public function destroy(Deduction $deduction,$id)
    {
        Deduction::where('id', $id)->delete();
    }
    public function employeeDeduction()
    {
        // // $employee = Deduction::with('employees')->where('deduction_name','cash advance')->get();
        // $employee = Employee::with('deductions')->get();
        // $deduction = DB::table('deduction_employee')->get();
        // return $employee;
 
    //    $deduction_id = Deduction::where('deduction_name', 'cash-avance')->first('id');
    //     $deduction = DB::table('deduction_employee')->where('deduction_id', $ddeduction_id->id)->get();
        // $employee = Employee::where('id', $deduction_id->id)->get();
        // $employee = Employee::with('deductions')->where('deduction_name','cash-advance')->get();
        $employee = Deduction::with('employees')->where('deduction_name', 'cash-advance')->get();
        return $employee;
    }

    public function addDeduction(Request $request)
    {
        $date_deduct = Carbon::now()->toDateTimeString();
        $amount = $request->amount;
        $employee_id = $request->employee['id']; 

        $deduction_id = Deduction::where('deduction_name', 'cash-advance')->get();
        $employee = Employee::find($employee_id);
        $employee->deductions()->attach($deduction_id, ['amount' => $amount, 'date_deducted' => $date_deduct]);
    }

    public function updateDeduction(Request $request)
    {
        $employee_id = $request->id;
        $deduction_id = $request->pivot['deduction_id'];
        $amount = $request->pivot['amount'];
        $date = $request->pivot['date_deducted'];
        $date_deduct = Carbon::now()->toDateTimeString();

        DB::table('deduction_employee')->where([['date_deducted', $date], ['employee_id', $employee_id], ['deduction_id', $deduction_id]])->delete();

        $deduction = Deduction::find($deduction_id);
        $employee = Employee::find($employee_id);
        $employee->deductions()->attach($deduction, ['amount' => $amount, 'date_deducted' => $date_deduct]);

        return response()->json(['message' => 'cash advance successfully updated!']);
    }

    public function deleteDeduction(Request $request)
    {
        $employee_id = $request->id;
        $deduction_id = $request->pivot['deduction_id'];
        $amount = $request->pivot['amount'];
        $date = $request->pivot['date_deducted'];

        DB::table('deduction_employee')->where([['date_deducted', $date], ['employee_id', $employee_id], ['deduction_id', $deduction_id], ['amount', $amount]])->delete();

        return response()->json(['message' => 'cash advance successfully deleted!']);
    }
}
