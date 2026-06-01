<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Payment;
use App\Models\WaterEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'monthly';
        $year = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;
        $season = $request->season;
        $farmerId = $request->farmer_id;

        $entriesQuery = WaterEntry::with('farmer')
            ->when($farmerId, fn($q) => $q->where('farmer_id', $farmerId));

        $paymentsQuery = Payment::with('farmer')
            ->when($farmerId, fn($q) => $q->where('farmer_id', $farmerId));

        if ($type === 'daily') {
            $date = $request->date ?? now()->toDateString();
            $entriesQuery->whereDate('supply_date', $date);
            $paymentsQuery->whereDate('payment_date', $date);
            $label = "দৈনিক রিপোর্ট: " . Carbon::parse($date)->format('d/m/Y');
        } elseif ($type === 'monthly') {
            $entriesQuery->whereYear('supply_date', $year)->whereMonth('supply_date', $month);
            $paymentsQuery->whereYear('payment_date', $year)->whereMonth('payment_date', $month);
            $label = "মাসিক রিপোর্ট: " . Carbon::create($year, $month)->format('F Y');
        } elseif ($type === 'seasonal') {
            $entriesQuery->where('season', $season);
            $paymentsQuery->whereHas('waterEntry', fn($q) => $q->where('season', $season));
            $label = "সিজন রিপোর্ট: $season";
        } else {
            $from = $request->from ?? now()->startOfYear()->toDateString();
            $to = $request->to ?? now()->toDateString();
            $entriesQuery->whereBetween('supply_date', [$from, $to]);
            $paymentsQuery->whereBetween('payment_date', [$from, $to]);
            $label = "কাস্টম রিপোর্ট: $from — $to";
        }

        $entries = $entriesQuery->latest('supply_date')->get();
        $payments = $paymentsQuery->latest('payment_date')->get();

        $totalBilled = $entries->sum('total_amount');
        $totalPaid = $payments->sum('amount');
        $totalDue = $totalBilled - $totalPaid;
        $totalHours = $entries->sum('hours');

        $farmers = Farmer::orderBy('name')->get();

        return view('reports.index', compact(
            'entries', 'payments', 'totalBilled', 'totalPaid', 'totalDue',
            'totalHours', 'label', 'type', 'farmers',
            'year', 'month', 'season', 'farmerId'
        ));
    }
}
