<?php

namespace App\Http\Controllers;

use App\Models\IvfPackage;
use App\Models\Patient;
use App\Models\PatientPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    public function index()
    {
        $packages = IvfPackage::withCount('patientPackages')->orderBy('name')->paginate(20);
        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:200',
            'description'       => 'nullable|string',
            'total_cost'        => 'required|numeric|min:0',
            'included_services' => 'nullable|string',
            'duration_days'     => 'nullable|integer|min:1',
            'status'            => 'required|in:active,inactive',
        ]);
        IvfPackage::create($data);
        return redirect()->route('packages.index')->with('success', 'Package created.');
    }

    public function edit(IvfPackage $package)
    {
        return view('packages.edit', compact('package'));
    }

    public function update(Request $request, IvfPackage $package)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:200',
            'description'       => 'nullable|string',
            'total_cost'        => 'required|numeric|min:0',
            'included_services' => 'nullable|string',
            'duration_days'     => 'nullable|integer|min:1',
            'status'            => 'required|in:active,inactive',
        ]);
        $package->update($data);
        return redirect()->route('packages.index')->with('success', 'Package updated.');
    }

    public function assign(Request $request)
    {
        $packages = IvfPackage::where('status','active')->orderBy('name')->get();
        $patient  = null;
        if ($request->patient_id) {
            $patient = Patient::find($request->patient_id);
        }
        return view('packages.assign', compact('packages','patient'));
    }

    public function assignStore(Request $request)
    {
        $data = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'ivf_package_id' => 'required|exists:ivf_packages,id',
            'total_amount'   => 'required|numeric|min:0',
            'discount'       => 'nullable|numeric|min:0',
            'start_date'     => 'nullable|date',
            'notes'          => 'nullable|string',
        ]);

        $existing = PatientPackage::where('patient_id', $data['patient_id'])
            ->where('status', 'active')->exists();
        if ($existing) {
            return back()->withErrors(['patient_id' => 'This patient already has an active package.'])->withInput();
        }

        $data['assigned_by'] = Auth::id();
        $data['discount']    = $data['discount'] ?? 0;

        PatientPackage::create($data);
        return redirect()->route('billing.patient', $data['patient_id'])->with('success', 'Package assigned successfully.');
    }
}
