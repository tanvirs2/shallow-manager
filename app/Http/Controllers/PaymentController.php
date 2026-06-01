<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\Payment;
use App\Models\WaterEntry;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with(['farmer', 'waterEntry'])
            ->when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->from, fn($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->latest('payment_date')
            ->paginate(20)
            ->withQueryString();

        $farmers = Farmer::orderBy('name')->get();
        $totalAmount = Payment::when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->from, fn($q) => $q->whereDate('payment_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('payment_date', '<=', $request->to))
            ->sum('amount');

        return view('payments.index', compact('payments', 'farmers', 'totalAmount'));
    }

    public function create(Request $request)
    {
        $farmers = Farmer::where('is_active', true)->orderBy('name')->get();
        $selectedFarmer = $request->farmer_id ? Farmer::with('waterEntries')->find($request->farmer_id) : null;
        $dueEntries = $selectedFarmer
            ? $selectedFarmer->waterEntries->filter(fn($e) => $e->due_amount > 0)
            : collect();

        return view('payments.create', compact('farmers', 'selectedFarmer', 'dueEntries'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farmer_id'      => 'required|exists:farmers,id',
            'water_entry_id' => 'nullable|exists:water_entries,id',
            'amount'         => 'required|numeric|min:1',
            'payment_date'   => 'required|date',
            'method'         => 'required|in:cash,bkash,nagad,rocket,bank,other',
            'reference'      => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
        ]);

        Payment::create($data);
        return redirect()->route('payments.index')->with('success', 'পেমেন্ট সফলভাবে রেকর্ড করা হয়েছে।');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')->with('success', 'পেমেন্ট মুছে ফেলা হয়েছে।');
    }

    public function farmerDue(Request $request)
    {
        $farmer = Farmer::with('waterEntries')->findOrFail($request->farmer_id);
        $dueEntries = $farmer->waterEntries->filter(fn($e) => $e->due_amount > 0)
            ->map(fn($e) => [
                'id'         => $e->id,
                'label'      => $e->supply_date->format('d/m/Y') . ' — ' . $e->hours . ' ঘণ্টা (বাকি: ৳' . number_format($e->due_amount, 2) . ')',
                'due_amount' => $e->due_amount,
            ])->values();

        return response()->json([
            'due_entries' => $dueEntries,
            'total_due'   => $farmer->total_due,
        ]);
    }
}
