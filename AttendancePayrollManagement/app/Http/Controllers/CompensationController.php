<?php

namespace App\Http\Controllers;

use App\Compensation;
use Illuminate\Http\Request;

class CompensationController extends Controller
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
        $compensation = new Compensation();

        $compensation->benefit_id = $request->benefit_id;
        $compensation->from_amount = $request->from_amount;
        $compensation->to_amount = $request->to_amount;
        $compensation->employee_share = $request->employee_share;
        $compensation->employer_share = $request->employer_share;
       
        $compensation->save();
        return response()->json(['message'=>'success','data'=>$compensation]);
    }

   
    public function show(Compensation $compensation)
    {
        return Compensation::all();
    }

    
    public function edit(Compensation $compensation)
    {
        //
    }

    
    public function update(Request $request, Compensation $compensation,$id)
    {
        Compensation::where('id', $id)->update($request->all());
    }

    
    public function destroy(Compensation $compensation,$id)
    {
        Compensation::where('id', $id)->delete();
    }
}
