<?php

namespace App\Http\Controllers;

use App\Payslip;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PayslipController extends Controller
{
    public function index()
    {
        return Payslip::with(['payroll', 'employee'])->get();
    }

    // public function payslip(Request $request)
    // {
    //     $start_date = $request->start_date;
    //     $end_date = $request->end_date;

    //     $payrollID = Payroll::where('start_date', $start_date)->where('end_date', $end_date)->first('id');
    //     if($payrollID != null)
    //     {
    //         $payslip = Payslip::with('employee')->with('payroll')->where('payroll_id', $payrollID->id)->get();
    //         return $payslip;
    //     }
    //     else
    //     {
    //         $latestPayroll = Payroll::latest('id')->first();
    //         $payslip = Payslip::with('employee')->with('payroll')->where('payroll_id', $latestPayroll->id)->get();
    //         return $payslip;
    //     }
    // }

    public function payslips($id)
    {
        return Payslip::with(['employee', 'payroll'])->where('payroll_id', $id)->get();
    }

    public function employeePayslip($id)
    {
        return Payslip::with('employee')->with('payroll')->find($id);
    }

    public function employeeDeduction($date)
    {
        return DB::table('deduction_employee')->where('date_deducted', $date)->get();
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $emp_id = $request->employee['id'];
        $date = new DateTime($request->updated_at);
        $dateTime = $date->format('Y-m-d H:i:s');

        Payslip::destroy($id);
        // DB::table('deduction_employee')->where('date_deducted', 'like', '%'.$dateTime.'%')->where('employee_id', $emp_id)->delete();
        DB::table('deduction_employee')->where('date_deducted', $dateTime)->where('employee_id', $emp_id)->delete();
        return response()->json(['message' => "employee's payslip deleted successfully"]);
    }
}
