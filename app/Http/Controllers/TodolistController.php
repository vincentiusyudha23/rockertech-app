<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use App\Models\Todolist;
use Illuminate\Support\Str;
use App\Events\CommentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TodolistController extends Controller
{
    private function getTodolistData()
    {
        $user = Auth::user()->employee;
        $todolist = Todolist::orderBy('index_task')
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
                    'created_at' => $item->created_at->format('D, d M Y'),
                ];
            })->groupBy('status');

        return $todolist;
    }

    private function getAllComment()
    {
        return Comments::latest()
            ->get()->map(function($comment){
                return [
                    'todo_id' => $comment->todo_id,
                    'user_id' => $comment->user_id,
                    'name' => $comment->user?->employee?->name,
                    'image' => get_data_image($comment->user?->employee?->image)['img_url'],
                    'content' => $comment->content,
                    'created_at' => $comment->created_at->format('H:i'),
                ];
            })->groupBy('todo_id')->toArray();
    }

    public function index()
    {
        return view('employe.todolist.index')->with([
            'todolist' => $this->getTodolistData(),
            'allComments' => $this->getAllComment()
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

            collect($data)->each(function($item){
                Todolist::find($item['todolist_id'])
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

    public function sendComments(Request $request)
    {
        $request->validate([
            'todolist_id' => 'required',
            'content' => 'required'
        ]);

        $todolist = Todolist::find($request->todolist_id);
        $user = Auth::user();

        if($todolist){
            $comments = $todolist->comments()->create([
                'user_id' => $user->id,
                'content' => $request->content
            ]);

            $comments->load('user');

            broadcast(new CommentEvent($this->getAllComment()))->toOthers();
            
            return response()->json([
                'data' => $this->getAllComment(),
            ], 200);
        } else {
            return response()->json([
                'type' => 'error',
                'msg' => 'Todolist is not found'
            ], 422);
        }
    }
}
