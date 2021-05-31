<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
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

        Position::create($request->all());
        return response()->json(['message' => 'position added successfully']);
    }

    public function show(Position $position)
    {
       return Position::all();
    }

    public function edit(Position $position)
    {
        //
    }

    public function update(Request $request, $id)
    {
        Position::where('id', $id)->update($request->all());
        return response()->json(['message' => 'position updated successfully']);
    }

    public function destroy($id)
    {
        Position::destroy($id);
        return response()->json(['message' => 'position deleted successfully']);
    }
}
