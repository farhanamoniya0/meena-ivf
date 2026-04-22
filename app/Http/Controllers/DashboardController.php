<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Consultant;
use App\Models\LabReport;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Requisition;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $today = now()->toDateString();

        // Consultant role → show dedicated consultant dashboard
        if (in_array($user->role, ['consultant', 'doctor'])) {
            $consultant = Consultant::where('email', $user->email)->first();
            $periods    = $this->buildConsultantPeriods($consultant);
            return view('dashboard.consultant', compact('user', 'consultant', 'periods'));
        }

        $billRevToday    = Bill::whereDate('payment_date', $today)->where('paid_amount', '>', 0)->sum('paid_amount');
        $packageRevToday = Payment::whereDate('created_at', $today)->where('status', 'approved')->sum('amount');

        $stats = [
            'today_patients'        => Patient::whereDate('created_at', $today)->count(),
            'today_appointments'    => Appointment::where('appointment_date', $today)->count(),
            'today_revenue'         => $billRevToday + $packageRevToday,
            'pending_tasks'         => Task::where('assigned_to', $user->id)->where('status','pending')->count(),
            'my_tasks'              => Task::where('assigned_to', $user->id)->whereIn('status',['pending','in-progress'])->with('patient')->latest()->take(5)->get(),
            'today_appts'           => Appointment::where('appointment_date', $today)->with(['patient','consultant'])->orderBy('appointment_time')->get(),
            'total_patients'        => Patient::count(),
            'active_packages'       => \App\Models\PatientPackage::where('status','active')->count(),
            'pending_approvals'     => Payment::where('status','pending')->count(),
            'low_stock'             => Medicine::get()->filter(fn($m) => $m->is_low_stock ?? false)->count(),
            'my_monthly_collection' => Bill::where('created_by', $user->id)
                                          ->whereMonth('payment_date', now()->month)
                                          ->whereYear('payment_date', now()->year)
                                          ->where('paid_amount', '>', 0)
                                          ->sum('paid_amount'),
            'today_new_patients'    => Patient::whereDate('created_at', $today)->with('consultant')->latest()->get(),
            'advance_total'         => Bill::where('payment_method', 'advance')->whereMonth('created_at', now()->month)->sum('paid_amount'),
            // Lab Report stats
            'lab_pending'           => LabReport::where('status','pending')->count(),
            'lab_processing'        => LabReport::whereIn('status',['collected','processing'])->count(),
            'lab_ready'             => LabReport::where('status','ready')->count(),
            'lab_ready_reports'     => LabReport::where('status','ready')->with(['patient','reporter'])->latest('reported_at')->take(10)->get(),
        ];

        $low_stock_meds   = Medicine::with('batches')->get()->filter(fn($m) => $m->total_stock <= $m->reorder_level)->take(5);
        $expiring_batches = MedicineBatch::with('medicine')->where('expiry_date', '<=', now()->addDays(30))->where('quantity','>',0)->orderBy('expiry_date')->take(5)->get();
        $pending_reqs     = Requisition::where('status','pending')->with(['medicine','requestedBy'])->latest()->take(5)->get();

        return view('dashboard.index', compact('stats','low_stock_meds','expiring_batches','pending_reqs','user'));
    }

    private function buildConsultantPeriods(?Consultant $consultant): array
    {
        $tomorrow  = now()->addDay()->toDateString();
        $today     = now()->toDateString();
        $mStart    = now()->startOfMonth()->toDateString();
        $mEnd      = now()->endOfMonth()->toDateString();
        $pmStart   = now()->subMonth()->startOfMonth()->toDateString();
        $pmEnd     = now()->subMonth()->endOfMonth()->toDateString();

        return [
            [
                'label' => 'Tomorrow\'s Schedule',
                'icon'  => 'bi-calendar-event',
                'color' => '#7c3aed',
                'bg'    => '#f3e8ff',
                'stats' => $this->apptStats($consultant, $tomorrow, $tomorrow),
            ],
            [
                'label' => 'Today',
                'icon'  => 'bi-calendar-check',
                'color' => '#0369a1',
                'bg'    => '#e0f2fe',
                'stats' => $this->apptStats($consultant, $today, $today),
            ],
            [
                'label' => 'This Month — ' . now()->format('F Y'),
                'icon'  => 'bi-calendar-month',
                'color' => '#047857',
                'bg'    => '#d1fae5',
                'stats' => $this->apptStats($consultant, $mStart, $mEnd),
            ],
            [
                'label' => 'Previous Month — ' . now()->subMonth()->format('F Y'),
                'icon'  => 'bi-calendar-minus',
                'color' => '#b45309',
                'bg'    => '#fef3c7',
                'stats' => $this->apptStats($consultant, $pmStart, $pmEnd),
            ],
            [
                'label' => 'Lifetime Total',
                'icon'  => 'bi-graph-up-arrow',
                'color' => '#be185d',
                'bg'    => '#fce7f3',
                'stats' => $this->apptStats($consultant, null, null),
            ],
        ];
    }

    private function apptStats(?Consultant $consultant, ?string $from, ?string $to): array
    {
        $base = Appointment::query();
        if ($consultant) {
            $base->where('consultant_id', $consultant->id);
        }
        if ($from && $to) {
            $base->whereBetween('appointment_date', [$from, $to]);
        }

        $types = ['new_patient', 'followup', 'scan', 'iui', 'stimulation'];
        $result = ['total' => (clone $base)->count()];
        foreach ($types as $type) {
            $result[$type] = (clone $base)->where('type', $type)->count();
        }
        return $result;
    }
}
