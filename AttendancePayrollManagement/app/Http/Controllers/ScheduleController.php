<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
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
            'shift'     => ['required'],
            'time_in'   => ['required'],
            'time_out'  => ['required']
        ]);

        Schedule::create($request->all());
        return response()->json(['message' => 'schedule added successfully']);
    }

    public function show(Schedule $schedule)
    {
        return Schedule::all();
    }

    public function edit(Schedule $schedule)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Schedule::where('id', $id)->update($request->all());
        return response()->json(['message' => 'schedule updated successfully']);
    }

    public function destroy(Schedule $schedule, $id)
    {
        Schedule::destroy($id);
        return response()->json(['message' => 'schedule deleted successfully']);
    }
}
