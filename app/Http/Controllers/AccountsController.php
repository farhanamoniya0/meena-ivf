<?php

namespace App\Http\Controllers;

use App\Models\DailyClosing;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    public function dashboard()
    {
        $today     = today()->toDateString();
        $yesterday = today()->subDay()->toDateString();

        $todayPayments = Payment::whereDate('created_at', $today)->where('status','approved');
        $summary = [
            'cash'   => (clone $todayPayments)->where('payment_method','cash')->sum('amount'),
            'bank'   => (clone $todayPayments)->where('payment_method','bank')->sum('amount'),
            'card'   => (clone $todayPayments)->where('payment_method','card')->sum('amount'),
            'bkash'  => (clone $todayPayments)->where('payment_method','bkash')->sum('amount'),
            'nagad'  => (clone $todayPayments)->where('payment_method','nagad')->sum('amount'),
            'rocket' => (clone $todayPayments)->where('payment_method','rocket')->sum('amount'),
            'total'  => (clone $todayPayments)->sum('amount'),
            'count'  => (clone $todayPayments)->count(),
        ];

        $recentPayments   = Payment::with(['patient','receivedBy'])->where('status','approved')->latest()->take(10)->get();
        $pendingPayments  = Payment::with(['patient','receivedBy'])->where('status','pending')->latest()->get();
        $todayClosed      = DailyClosing::where('closing_date', $today)->first();
        $closingHistory   = DailyClosing::orderByDesc('closing_date')->take(10)->get();

        $monthTotal = Payment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status','approved')->sum('amount');

        return view('accounts.dashboard', compact(
            'summary','recentPayments','pendingPayments','todayClosed','closingHistory','monthTotal'
        ));
    }

    public function closeDay(Request $request)
    {
        $today = today()->toDateString();

        $existing = DailyClosing::where('closing_date', $today)->first();
        if ($existing && $existing->status === 'closed') {
            return back()->with('error', 'Today has already been closed.');
        }

        $payments = Payment::whereDate('created_at', $today)->where('status','approved');

        $closing = DailyClosing::updateOrCreate(
            ['closing_date' => $today],
            [
                'total_cash'          => (clone $payments)->where('payment_method','cash')->sum('amount'),
                'total_bank'          => (clone $payments)->where('payment_method','bank')->sum('amount'),
                'total_card'          => (clone $payments)->where('payment_method','card')->sum('amount'),
                'total_bkash'         => (clone $payments)->where('payment_method','bkash')->sum('amount'),
                'total_nagad'         => (clone $payments)->where('payment_method','nagad')->sum('amount'),
                'total_rocket'        => (clone $payments)->where('payment_method','rocket')->sum('amount'),
                'total_amount'        => (clone $payments)->sum('amount'),
                'total_transactions'  => (clone $payments)->count(),
                'closed_by'           => Auth::id(),
                'notes'               => $request->notes,
                'status'              => 'closed',
                'closed_at'           => now(),
            ]
        );

        return redirect()->route('accounts.dashboard')->with('success', 'Day closed successfully. Total: ৳' . number_format($closing->total_amount, 2));
    }

    public function history()
    {
        $closings = DailyClosing::with('closedBy')->orderByDesc('closing_date')->paginate(30);
        return view('accounts.history', compact('closings'));
    }

    public function approvePayment(Request $request, Payment $payment)
    {
        $payment->update([
            'status'      => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'Payment approved.');
    }

    public function rejectPayment(Request $request, Payment $payment)
    {
        $payment->update(['status' => 'rejected']);
        return back()->with('success', 'Payment rejected.');
    }
}
