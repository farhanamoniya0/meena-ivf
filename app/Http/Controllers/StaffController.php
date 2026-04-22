<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = Staff::query();
        if ($request->search) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%$s%")
                ->orWhere('employee_id','like',"%$s%")
                ->orWhere('designation','like',"%$s%")
                ->orWhere('phone','like',"%$s%"));
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->department) $query->where('department', $request->department);

        $staff       = $query->orderBy('name')->paginate(20)->withQueryString();
        $departments = Staff::distinct()->pluck('department')->filter()->sort();
        return view('staff.index', compact('staff','departments'));
    }

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'designation' => 'required|string|max:200',
            'department'  => 'nullable|string|max:200',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:200',
            'nid'         => 'nullable|string|max:30',
            'join_date'   => 'nullable|date',
            'salary'      => 'nullable|numeric|min:0',
            'address'     => 'nullable|string',
            'notes'       => 'nullable|string',
            'status'      => 'required|in:active,inactive,terminated',
            'photo'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('staff', 'public');
        }

        Staff::create($data);
        return redirect()->route('staff.index')->with('success', 'Staff member added.');
    }

    public function edit(Staff $staff)
    {
        return view('staff.edit', compact('staff'));
    }

    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'designation' => 'required|string|max:200',
            'department'  => 'nullable|string|max:200',
            'phone'       => 'nullable|string|max:20',
            'email'       => 'nullable|email|max:200',
            'nid'         => 'nullable|string|max:30',
            'join_date'   => 'nullable|date',
            'salary'      => 'nullable|numeric|min:0',
            'address'     => 'nullable|string',
            'notes'       => 'nullable|string',
            'status'      => 'required|in:active,inactive,terminated',
            'photo'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($staff->photo) Storage::disk('public')->delete($staff->photo);
            $data['photo'] = $request->file('photo')->store('staff', 'public');
        } else {
            unset($data['photo']);
        }

        $staff->update($data);
        return redirect()->route('staff.index')->with('success', 'Staff member updated.');
    }
}
