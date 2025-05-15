<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TodolistController extends Controller
{
    private function getTodolistData()
    {
        $user = Auth::user()->employee;
        $todolist = $user->todolist()
            ->orderBy('index_task')
            ->get()
            ->transform(function($item){
                return [
                    'id' => $item->id,
                    'title' => Str::limit($item->title, 20, '...'),
                    'priority' => $item->priority,
                    'status' => $item->status,
                    'description' => $item->desc,
                    'image' => get_data_image($item->employe->image)['img_url'],
                    'name' => $item->employe->name,
                    'type' => $item->type ?? null,
                    'created_at' => $item->created_at->format('D, d M Y')
                ];
            })->groupBy('status');
        return $todolist;
    }

    public function index()
    {
        return view('employe.todolist.index')->with([
            'todolist' => $this->getTodolistData()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'priority' => 'required',
            'description' => 'required',
            'type' => 'required'
        ]);

        try{
            DB::beginTransaction();
            
            $user = Auth::user();

            $user->employee->todolist()->create([
                'title' => $request->title,
                'priority' => $request->priority,
                'desc' => $request->description,
                'type' => $request->type
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Create To-do List Successfully',
                'data' => $this->getTodolistData()
            ]);

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ], 422);
        }
    }

    public function updateTaskIndex(Request $request)
    {
        $data = $request->data;

        if(!empty($data)){
            $user = Auth::user()->employee;
            collect($data)->each(function($item) use ($user){
                $user->todolist()->find($item['todolist_id'])
                    ->update([
                        'index_task' => $item['index_task'],
                        'status' => $item['status']
                    ]);
            });

            return response()->json([
                'status' => 'success',
                'data' => $this->getTodolistData()
            ]);
        }

        return response()->json([
            'status' => 'error'
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required',
            'priority' => 'required',
            'description' => 'required',
            'type' => 'required'
        ]);

        $user = Auth::user()->employee;

        try{
            DB::beginTransaction();

            $user->todolist()
                ->find($request->id)
                ->update([
                    'title' => $request->title,
                    'priority' => $request->priority,
                    'desc' => $request->description,
                    'type' => $request->type
                ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'msg' => 'Edit To-do List Successfully',
                'data' => $this->getTodolistData()
            ]);

        } catch (\Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ], 422);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            Auth::user()->employee
                ->todolist()
                ->findOrFail($request->id)
                ->delete();

            return response()->json([
                'status' => 'success',
                'msg' => 'Delete To-do List Successfully',
                'data' => $this->getTodolistData()
            ]);

        } catch (\Exception $e){
            return response()->json([
                'status' => 'error',
                'msg' => 'Delete To-do List Failed'
            ], 422);
        }
    }
}
