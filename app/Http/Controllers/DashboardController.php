<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Payment;
use App\Models\WaterEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $farmerIds = Farmer::where('user_id', $userId)->pluck('id');

        $todayCollection = Payment::whereIn('farmer_id', $farmerIds)
            ->whereDate('payment_date', $today)->sum('amount');
        $monthCollection = Payment::whereIn('farmer_id', $farmerIds)
            ->whereBetween('payment_date', [$startOfMonth, $endOfMonth])->sum('amount');
        $totalBilled = WaterEntry::whereIn('farmer_id', $farmerIds)->sum('total_amount');
        $totalPaid   = Payment::whereIn('farmer_id', $farmerIds)->sum('amount');
        $totalDue    = $totalBilled - $totalPaid;

        $todayEntries = WaterEntry::with('farmer')
            ->whereIn('farmer_id', $farmerIds)
            ->whereDate('supply_date', $today)
            ->latest()
            ->take(5)
            ->get();

        $topDue = Farmer::with(['waterEntries', 'payments'])
            ->where('user_id', $userId)
            ->get()
            ->filter(fn($f) => $f->total_due > 0)
            ->sortByDesc('total_due')
            ->take(5);

        $recentPayments = Payment::with('farmer')
            ->whereIn('farmer_id', $farmerIds)
            ->latest()
            ->take(5)
            ->get();

        $isSQLite = DB::getDriverName() === 'sqlite';
        $monthExpr = $isSQLite ? "strftime('%m', supply_date)" : "MONTH(supply_date)";
        $monthlyData = WaterEntry::selectRaw("$monthExpr as month, SUM(total_amount) as billed")
            ->whereIn('farmer_id', $farmerIds)
            ->whereYear('supply_date', now()->year)
            ->groupByRaw($monthExpr)
            ->pluck('billed', 'month');

        return view('dashboard', compact(
            'todayCollection', 'monthCollection',
            'totalBilled', 'totalPaid', 'totalDue',
            'todayEntries', 'topDue', 'recentPayments', 'monthlyData'
        ));
    }
}
