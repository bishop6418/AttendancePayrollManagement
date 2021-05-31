<?php

namespace App\Http\Controllers;

use App\Leave;
use Illuminate\Http\Request;

class LeaveController extends Controller
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
        $leave = Leave::create($request->all());
        return response()->json(['message'=>'success','data'=>$leave]);
    }

    public function show(Leave $leave)
    {
        return Leave::all();
    }

    public function edit(Leave $leave)
    {
        //
    }

    public function update(Request $request, Leave $leave,$id)
    {
        Leave::where('id', $id)->update($request->all());
    }

    public function destroy(Leave $leave,$id)
    {
        Leave::all()->where('employee_id', $id)->delete();
    }
}
