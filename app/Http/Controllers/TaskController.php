<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);
    
        $imagePath = null;
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tasks', 'public');
        }
    
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imagePath
        ]);
    
        return response()->json(['success' => true]);
    }

    public function destroy($id)
{
    $task = Task::findOrFail($id);

    if ($task->image) {
        Storage::disk('public')->delete($task->image);
    }

    $task->delete();

    return response()->json(['success' => true]);
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
