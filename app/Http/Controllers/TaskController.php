<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Task::all();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {


        $task = Task::create($request->all());
        return $task;
    }
    public function indexPage()
    {
        $tasks = Task::all();
        return view('tasks', compact('tasks'));
    }

    public function complete($id)
    {
        $task = Task::find($id);
        $task->update(['is_completed' => true]);

        return $task;
    }
}
