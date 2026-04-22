<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\PatientPackage;
use App\Models\Payment;
use App\Models\Requisition;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = today()->toDateString();
        $stats = [
            'today_patients'    => Patient::whereDate('created_at', $today)->count(),
            'today_revenue'     => Payment::whereDate('created_at', $today)->where('status','approved')->sum('amount'),
            'pending_payments'  => Payment::where('status','pending')->count(),
            'pending_reqs'      => Requisition::where('status','pending')->count(),
            'total_patients'    => Patient::count(),
            'active_packages'   => PatientPackage::where('status','active')->count(),
            'today_appts'       => Appointment::where('appointment_date', $today)->count(),
            'pending_tasks'     => Task::whereIn('status',['pending','in-progress'])->count(),
        ];

        $pendingPayments  = Payment::with(['patient','receivedBy'])->where('status','pending')->latest()->take(10)->get();
        $pendingReqs      = Requisition::with(['medicine','requestedBy'])->where('status','pending')->latest()->take(10)->get();
        $departments      = Department::with('head')->where('status','active')->get();
        $lowMeds          = Medicine::with('batches')->get()->filter(fn($m) => $m->total_stock <= $m->reorder_level)->take(5);
        $expiringBatches  = MedicineBatch::with('medicine')->where('expiry_date','<=', now()->addDays(30))->where('quantity','>',0)->orderBy('expiry_date')->take(5)->get();

        return view('admin.dashboard', compact('stats','pendingPayments','pendingReqs','departments','lowMeds','expiringBatches'));
    }

    public function users()
    {
        $users = User::orderBy('name')->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function createUser()
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:200',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'nullable|string|max:20',
            'role'      => 'required|in:admin,doctor,consultant,billing,accountant,pharmacy,lab,reception',
            'password'  => 'required|string|min:6|confirmed',
            'is_active' => 'nullable|boolean',
        ]);
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);
        User::create($data);
        return redirect()->route('admin.users')->with('success', 'User created.');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function departments()
    {
        $departments = Department::with('head')->orderBy('name')->get();
        $users       = User::where('is_active', true)->orderBy('name')->get();
        return view('admin.departments', compact('departments','users'));
    }

    public function storeDepartment(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'code'        => 'required|string|max:20|unique:departments,code',
            'description' => 'nullable|string',
            'head_id'     => 'nullable|exists:users,id',
        ]);
        Department::create($data);
        return back()->with('success', 'Department added.');
    }

    public function approvePayment(Payment $payment)
    {
        $payment->update(['status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return back()->with('success', 'Payment approved.');
    }

    public function rejectPayment(Payment $payment)
    {
        $payment->update(['status' => 'rejected']);
        return back()->with('success', 'Payment rejected.');
    }

    public function approveRequisition(Requisition $requisition)
    {
        $requisition->update(['status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return back()->with('success', 'Requisition approved.');
    }

    public function rejectRequisition(Requisition $requisition)
    {
        $requisition->update(['status' => 'rejected', 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return back()->with('success', 'Requisition rejected.');
    }
}
