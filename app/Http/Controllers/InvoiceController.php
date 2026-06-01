<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\PumpOwner;
use App\Models\WaterEntry;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(WaterEntry $waterEntry)
    {
        $waterEntry->load(['farmer', 'payments']);
        $owner = PumpOwner::first();
        return view('invoices.show', compact('waterEntry', 'owner'));
    }

    public function pdf(WaterEntry $waterEntry)
    {
        $waterEntry->load(['farmer', 'payments']);
        $owner = PumpOwner::first();

        $pdf = Pdf::loadView('invoices.pdf', compact('waterEntry', 'owner'))
            ->setPaper('a5', 'portrait');

        $filename = 'invoice-' . $waterEntry->id . '-' . $waterEntry->supply_date->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    public function farmerBill(Request $request, Farmer $farmer)
    {
        $month = $request->month ?? now()->month;
        $year = $request->year ?? now()->year;

        $entries = $farmer->waterEntries()
            ->whereYear('supply_date', $year)
            ->whereMonth('supply_date', $month)
            ->with('payments')
            ->latest('supply_date')
            ->get();

        $owner = PumpOwner::first();
        $totalBilled = $entries->sum('total_amount');
        $totalPaid = $farmer->payments()->whereYear('payment_date', $year)->whereMonth('payment_date', $month)->sum('amount');
        $totalDue = $totalBilled - $totalPaid;

        $pdf = Pdf::loadView('invoices.farmer-bill', compact(
            'farmer', 'entries', 'owner', 'totalBilled', 'totalPaid', 'totalDue', 'month', 'year'
        ))->setPaper('a4', 'portrait');

        $filename = 'bill-' . $farmer->id . '-' . $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '.pdf';
        return $pdf->download($filename);
    }
}
