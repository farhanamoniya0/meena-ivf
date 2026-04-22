<?php

namespace App\Http\Controllers;

use App\Models\Consultant;
use Illuminate\Http\Request;

class ConsultantController extends Controller
{
    public function index()
    {
        $consultants = Consultant::withCount('patients')->orderBy('name')->paginate(20);
        return view('consultants.index', compact('consultants'));
    }

    public function create()
    {
        return view('consultants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:200',
            'specialty'        => 'required|string|max:200',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email|max:200',
            'consultation_fee' => 'required|numeric|min:0',
            'qualifications'   => 'nullable|string|max:500',
            'bio'              => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('consultants','public');
        }

        Consultant::create($data);
        return redirect()->route('consultants.index')->with('success', 'Consultant added.');
    }

    public function edit(Consultant $consultant)
    {
        return view('consultants.edit', compact('consultant'));
    }

    public function update(Request $request, Consultant $consultant)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:200',
            'specialty'        => 'required|string|max:200',
            'phone'            => 'nullable|string|max:20',
            'email'            => 'nullable|email',
            'consultation_fee' => 'required|numeric|min:0',
            'qualifications'   => 'nullable|string',
            'bio'              => 'nullable|string',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('consultants','public');
        }

        $consultant->update($data);
        return redirect()->route('consultants.index')->with('success', 'Consultant updated.');
    }
}
