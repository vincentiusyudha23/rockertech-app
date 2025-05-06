<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodolistController extends Controller
{
    public function index()
    {
        return view('employe.todolist.index');
    }

    public function store(Request $request)
    {
        $request->validated([
            'title' => 'required|string',
            'priority' => 'required',
            'due_date' => 'required',
            'description' => 'required',
            'day' => 'required'
        ]);

        try{
            
        } catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ], 422);
        }
    }
}
