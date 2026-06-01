<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Payment;
use App\Models\WaterEntry;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $todayCollection = Payment::whereDate('payment_date', $today)->sum('amount');
        $monthCollection = Payment::whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalBilled = WaterEntry::sum('total_amount');
        $totalPaid = Payment::sum('amount');
        $totalDue = $totalBilled - $totalPaid;

        $todayEntries = WaterEntry::with('farmer')
            ->whereDate('supply_date', $today)
            ->latest()
            ->take(5)
            ->get();

        $topDue = Farmer::with(['waterEntries', 'payments'])
            ->get()
            ->filter(fn($f) => $f->total_due > 0)
            ->sortByDesc('total_due')
            ->take(5);

        $recentPayments = Payment::with('farmer')
            ->latest()
            ->take(5)
            ->get();

        $monthlyData = WaterEntry::selectRaw("strftime('%m', supply_date) as month, SUM(total_amount) as billed")
            ->whereYear('supply_date', now()->year)
            ->groupByRaw("strftime('%m', supply_date)")
            ->pluck('billed', 'month');

        return view('dashboard', compact(
            'todayCollection', 'monthCollection',
            'totalBilled', 'totalPaid', 'totalDue',
            'todayEntries', 'topDue', 'recentPayments', 'monthlyData'
        ));
    }
}
