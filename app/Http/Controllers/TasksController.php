<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        
        if(Auth::check()) {
            $user = Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', [
           'task' => $task, 
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = Auth::id();
        
        $task->save();
        
        return redirect('/tasks');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if(Auth::id() !== $task->user_id) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this task.');
        }
        
        return view('tasks.show', [
           'task' => $task, 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if(Auth::id() !== $task->user_id) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this task.');
        }
        
        return view('tasks.edit', [
           'task' => $task, 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        $task = Task::findOrFail($id);
        
        if(Auth::id() !== $task->user_id) {
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this task.');
        }
        
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
        
        return redirect('/tasks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        
        if(Auth::id() !== $task->user_id) {
        return redirect()->route('dashboard')->with('error', 'You do not have permission to access this task.');
        }
        
        $task->delete();
        return redirect('/tasks')->with('success', 'Delete Successful');
    }
}
