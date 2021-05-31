<?php

namespace App\Http\Controllers;

use App\Payslip;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function index($id)
    {
        $payslip = Payslip::with(['employee', 'payroll'])->where('payroll_id', $id)->get();

        for($x = 0; $x < count($payslip); $x++)
        {
            $data["email"]      = $payslip[$x]->employee->email;
            $data["title"]      = "Payslip From Qonvex";
            $data["month"]      = "SALARY SLIP: ".$payslip[$x]->payroll->start_date." - ".$payslip[$x]->payroll->end_date;
            $data["name"]       = "NAME: ".$payslip[$x]->employee->first_name." ".$payslip[$x]->employee->last_name;
            $data["gross"]      = "GROSS: ".$payslip[$x]->gross_salary;
            $data["deduction"]  = "DEDUCTION: ".$payslip[$x]->total_deduction;
            $data["net"]        = "NET: ".$payslip[$x]->net_salary;

            Mail::send('emails.TestMail', $data, function($message)use($data) {
                $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
            });
        }

        dd('Mail sent successfully');

        // $data["email"] = ['ela.glen@yahoo.com', 'kickz_verse@yahoo.com'];
        // $data["title"] = "From Qonvex";
        // $data["body"] = "This is Demo";
 
        // $files = [
        //     public_path('files/160031367318.pdf'),
        //     public_path('files/1599882252.png'),
        // ];
  
        // Mail::send('emails.TestMail', $data, function($message)use($data) {
        //     $message->to($data["email"], $data["email"])
        //             ->subject($data["title"]);
 
        //     foreach ($files as $file){
        //         $message->attach($file);
        //     }
        // });
 
        // dd('Mail sent successfully');
    }
}
