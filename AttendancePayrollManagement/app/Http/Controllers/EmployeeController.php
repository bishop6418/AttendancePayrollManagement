<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Rate;
use App\User;
use Illuminate\Http\Request;

include 'Timezone.php';

class EmployeeController extends Controller
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
        $request->rate_id = Rate::latest('id')->get()->first()->id;
        $request->validate([
            'position_id'   => ['required'],
            'schedule_id'   => ['required'],

            'first_name'    => ['required'],
            'last_name'     => ['required'],
            'email'         => ['required', 'email', 'max:255', 'unique:employees'],
            'age'           => ['required', 'numeric'],
            'address'       => ['required'],
            'contact_number'=> ['required', 'numeric'],
            'gender'        => ['required'],
            'birthdate'     => ['required'],
            'marital_status'=> ['required'],
            'date_hired'    => ['required'],
            'leave_credits' => ['numeric'],
            'rate_type'     => ['required'],
            'basic_salary'  => ['required', 'numeric'],
            'additional'    => ['numeric'],
        ]);

        dd($request->image_path);
        Rate::create($request->all());
        $input = $request->all();
        $input['rate_id'] = Rate::latest('id')->get()->first()->id;
        Employee::create($input);
        return response()->json(['message' => 'employee added successfully']);
    }

    public function show()
    {
        // return Employee::with('position')->with('schedule')->get();
        return Employee::with(['position', 'schedule'])->get();
    }

    public function totalEmployees(Employee $employee)
    {
        return count(Employee::all());
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $rate_id = $request->rate_id;
        // Employee::where('id', $id)->update($request->all());
        Employee::where('id', $id)->update([
            'position_id'   => $request->position_id,
            'rate_id'       => $request->rate_id,
            'schedule_id'   => $request->schedule_id,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'email'         => $request->email,
            'age'           => $request->age,
            'address'       => $request->address,
            'contact_number'=> $request->contact_number,
            'gender'        => $request->gender,
            'birthdate'     => $request->birthdate,
            'marital_status'=> $request->marital_status,
            'date_hired'    => $request->date_hired,
            'leave_credits' => $request->leave_credits
        ]);
        // Rate::where('id', $rate_id)->update($request->all());
        Rate::where('id', $rate_id)->update([
            'rate_type'     => $request->rate['rate_type'],
            'basic_salary'  => $request->rate['basic_salary'],
            'additional'    => $request->rate['additional']
        ]);
        return response()->json(['message' => 'employee updated successfully']);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $email = $request->email;
        $rate_id = $request->rate_id;
        Employee::destroy($id);
        User::where('email', $email)->delete();
        Rate::destroy($rate_id);
        return response()->json(['message' => 'employee deleted successfully']);
    }

    public function get($id)
    {
        return Employee::with(['position', 'schedule', 'benefits', 'deductions', 'rate'])->find($id);
    }
    public function update_Image(Request $request)
    {
        Employee::where('id', $request->emp_id)
        ->update([ 'image_path' => $request->path]);
    }
}
