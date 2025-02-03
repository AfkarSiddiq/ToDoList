<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todolist;

class TodolistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Todolist::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($request)
    {
    
        Todolist::create([
            'title' => $request->title,
            'completed' => false
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $todo = Todolist::create([
            'title' => $request->title,
            'completed' =>$request->completed ? 1 : 0,
        ]);
    
        return response()->json($todo);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Todolist::where('id', $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        Todolist::where('id', $id)->update(['completed' => true]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $todo = Todolist::findOrFail($id);
        $todo->completed = $request->completed;
        $todo->save();
    
        return response()->json(['message' => 'Todo updated successfully', 'todo' => $todo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Todolist::where('id', $id)->delete();
    }
}
