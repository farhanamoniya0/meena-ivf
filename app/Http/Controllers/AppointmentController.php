<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Consultant;
use App\Models\Department;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $date  = $request->date ?? today()->toDateString();
        $query = Appointment::with(['patient','consultant','department'])
            ->where('appointment_date', $date);

        if ($request->consultant_id) $query->where('consultant_id', $request->consultant_id);
        if ($request->status)        $query->where('status', $request->status);

        $appointments = $query->orderBy('appointment_time')->paginate(30)->withQueryString();
        $consultants  = Consultant::where('status','active')->orderBy('name')->get();
        return view('appointments.index', compact('appointments','consultants','date'));
    }

    public function create()
    {
        $consultants = Consultant::where('status','active')->orderBy('name')->get();
        $departments = Department::where('status','active')->orderBy('name')->get();
        return view('appointments.create', compact('consultants','departments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'consultant_id'    => 'nullable|exists:consultants,id',
            'department_id'    => 'nullable|exists:departments,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'type'             => 'required|in:new_patient,followup,scan,iui,stimulation,ivf,opd,consultation,procedure',
            'notes'            => 'nullable|string',
        ]);
        $data['created_by'] = Auth::id();
        $data['status']     = 'scheduled';
        Appointment::create($data);
        return redirect()->route('appointments.index')->with('success', 'Appointment booked.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:scheduled,confirmed,completed,cancelled,no-show']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Status updated.');
    }
}
