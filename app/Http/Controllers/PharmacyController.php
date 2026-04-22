<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\MedicineAssignment;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PharmacyController extends Controller
{
    public function dashboard()
    {
        $medicines       = Medicine::with('batches')->where('status','active')->get();
        $lowStock        = $medicines->filter(fn($m) => $m->total_stock <= $m->reorder_level);
        $expiringBatches = MedicineBatch::with('medicine')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date')->get();
        $pendingReqs = Requisition::with(['medicine','requestedBy'])->where('status','pending')->latest()->get();
        return view('pharmacy.dashboard', compact('medicines','lowStock','expiringBatches','pendingReqs'));
    }

    public function medicines()
    {
        $medicines = Medicine::with('batches')->withCount('batches')->orderBy('name')->paginate(20);
        return view('pharmacy.medicines', compact('medicines'));
    }

    public function createMedicine()
    {
        return view('pharmacy.create-medicine');
    }

    public function storeMedicine(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:200',
            'generic_name'  => 'nullable|string|max:200',
            'brand'         => 'nullable|string|max:200',
            'category'      => 'nullable|string|max:100',
            'unit'          => 'required|string|max:20',
            'reorder_level' => 'required|integer|min:0',
            'description'   => 'nullable|string',
        ]);
        Medicine::create($data);
        return redirect()->route('pharmacy.medicines')->with('success', 'Medicine added.');
    }

    public function batches(Medicine $medicine)
    {
        $batches = $medicine->batches()->orderBy('expiry_date')->paginate(20);
        return view('pharmacy.batches', compact('medicine','batches'));
    }

    public function addBatch(Medicine $medicine)
    {
        return view('pharmacy.add-batch', compact('medicine'));
    }

    public function storeBatch(Request $request, Medicine $medicine)
    {
        $data = $request->validate([
            'batch_number'   => 'required|string|max:100',
            'expiry_date'    => 'required|date|after:today',
            'quantity'       => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
        ]);
        $medicine->batches()->create($data);
        return redirect()->route('pharmacy.batches', $medicine)->with('success', 'Batch added. Stock updated.');
    }

    public function assignForm()
    {
        $medicines = Medicine::where('status','active')->orderBy('name')->get();
        return view('pharmacy.assign', compact('medicines'));
    }

    public function assignStore(Request $request)
    {
        $data = $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'medicine_id'       => 'required|exists:medicines,id',
            'medicine_batch_id' => 'required|exists:medicine_batches,id',
            'quantity'          => 'required|integer|min:1',
            'notes'             => 'nullable|string',
        ]);

        $batch = MedicineBatch::findOrFail($data['medicine_batch_id']);
        if ($batch->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock in this batch.'])->withInput();
        }

        $data['assigned_by'] = Auth::id();
        MedicineAssignment::create($data);
        $batch->decrement('quantity', $data['quantity']);

        return back()->with('success', 'Medicine assigned to patient.');
    }

    public function requisitions()
    {
        $requisitions = Requisition::with(['medicine','requestedBy','approvedBy'])->latest()->paginate(20);
        return view('pharmacy.requisitions', compact('requisitions'));
    }

    public function requisitionStore(Request $request)
    {
        $data = $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity'    => 'required|integer|min:1',
            'reason'      => 'nullable|string',
        ]);
        $data['requested_by'] = Auth::id();
        Requisition::create($data);
        return back()->with('success', 'Requisition submitted.');
    }

    public function approveRequisition(Requisition $requisition)
    {
        $requisition->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Requisition approved.');
    }

    public function rejectRequisition(Requisition $requisition)
    {
        $requisition->update(['status' => 'rejected', 'approved_by' => Auth::id(), 'approved_at' => now()]);
        return back()->with('success', 'Requisition rejected.');
    }

    public function getBatches(Medicine $medicine)
    {
        $batches = $medicine->batches()->where('quantity', '>', 0)->orderBy('expiry_date')->get(['id','batch_number','expiry_date','quantity']);
        return response()->json($batches);
    }
}
