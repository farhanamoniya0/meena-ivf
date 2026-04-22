<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $myTasks  = Task::where('assigned_to', Auth::id())->with(['assignedBy','patient'])->latest()->paginate(20);
        return view('tasks.index', compact('myTasks'));
    }

    public function create()
    {
        $users       = User::where('is_active', true)->orderBy('name')->get();
        $departments = Department::where('status','active')->orderBy('name')->get();
        return view('tasks.create', compact('users','departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => 'required|string|max:200',
            'description'   => 'nullable|string',
            'assigned_to'   => 'required|exists:users,id',
            'patient_id'    => 'nullable|exists:patients,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority'      => 'required|in:low,medium,high,urgent',
            'due_date'      => 'nullable|date',
        ]);
        $data['assigned_by'] = Auth::id();
        $data['status']      = 'pending';
        Task::create($data);
        return redirect()->route('tasks.index')->with('success', 'Task assigned.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate(['status' => 'required|in:pending,in-progress,completed,cancelled']);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Task status updated.');
    }
}
