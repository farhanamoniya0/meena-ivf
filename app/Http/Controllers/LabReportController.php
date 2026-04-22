<?php

namespace App\Http\Controllers;

use App\Models\LabReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LabReport::with(['patient','collector','reporter','deliverer'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('sample_code', 'like', "%$s%")
                  ->orWhereHas('patient', fn($p) => $p->where('name','like',"%$s%")
                      ->orWhere('patient_code','like',"%$s%")
                      ->orWhere('phone','like',"%$s%"));
            });
        }

        $reports  = $query->paginate(20)->withQueryString();
        $counts   = [
            'pending'    => LabReport::where('status','pending')->count(),
            'collected'  => LabReport::where('status','collected')->count(),
            'processing' => LabReport::where('status','processing')->count(),
            'ready'      => LabReport::where('status','ready')->count(),
            'delivered'  => LabReport::where('status','delivered')->count(),
        ];
        return view('lab.reports.index', compact('reports','counts'));
    }

    public function create(Request $request)
    {
        $patient = $request->patient_id ? Patient::find($request->patient_id) : null;
        $patients = Patient::orderBy('name')->get(['id','patient_code','name','phone']);
        return view('lab.reports.create', compact('patient','patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_type'  => 'required|string',
            'notes'      => 'nullable|string',
        ]);

        LabReport::create([
            'patient_id' => $request->patient_id,
            'test_type'  => $request->test_type,
            'notes'      => $request->notes,
            'status'     => 'pending',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('lab.reports.index')->with('success', 'Lab report entry created.');
    }

    public function show(LabReport $report)
    {
        $report->load(['patient','collector','processor','reporter','deliverer','creator']);
        return view('lab.reports.show', compact('report'));
    }

    public function edit(LabReport $report)
    {
        $report->load('patient');
        return view('lab.reports.edit', compact('report'));
    }

    public function update(Request $request, LabReport $report)
    {
        $data = $request->validate([
            'report_data' => 'nullable|array',
            'notes'       => 'nullable|string',
        ]);

        $report->update([
            'report_data' => $data['report_data'] ?? $report->report_data,
            'notes'       => $data['notes'] ?? $report->notes,
        ]);

        return redirect()->route('lab.reports.show', $report)->with('success', 'Report data saved.');
    }

    public function advance(Request $request, LabReport $report)
    {
        $next = $report->next_status;
        if (!$next) {
            return back()->with('error', 'No further action available.');
        }

        $now    = now();
        $userId = Auth::id();
        $update = ['status' => $next];

        match ($next) {
            'collected'  => $update += ['collected_by'  => $userId, 'collected_at'  => $now],
            'processing' => $update += ['processed_by'  => $userId, 'processed_at'  => $now],
            'ready'      => $update += ['reported_by'   => $userId, 'reported_at'   => $now],
            'delivered'  => $update += ['delivered_by'  => $userId, 'delivered_at'  => $now],
            default      => null,
        };

        if ($next === 'ready') {
            $reportData = $request->input('report_data');
            if ($reportData) {
                $update['report_data'] = $reportData;
            }
            if (!$report->report_data && !$reportData) {
                return back()->with('error', 'Please fill in the report data before marking as Ready.');
            }
        }

        $report->update($update);

        $msg = match ($next) {
            'collected'  => 'Sample marked as collected.',
            'processing' => 'Processing started.',
            'ready'      => 'Report marked as ready. Front office notified.',
            'delivered'  => 'Report delivered to patient.',
            default      => 'Status updated.',
        };

        return redirect()->route('lab.reports.show', $report)->with('success', $msg);
    }

    public function print(LabReport $report)
    {
        $report->load(['patient','reporter']);
        return view('lab.reports.print', compact('report'));
    }

    public function readyList()
    {
        $reports = LabReport::where('status','ready')
                    ->with(['patient','reporter'])
                    ->latest('reported_at')
                    ->get();
        return view('lab.reports.ready', compact('reports'));
    }
}
