<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\PumpOwner;
use App\Models\WaterEntry;
use Illuminate\Http\Request;

class WaterEntryController extends Controller
{
    public function index(Request $request)
    {
        $entries = WaterEntry::with('farmer')
            ->when($request->farmer_id, fn($q) => $q->where('farmer_id', $request->farmer_id))
            ->when($request->from, fn($q) => $q->whereDate('supply_date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('supply_date', '<=', $request->to))
            ->when($request->season, fn($q) => $q->where('season', $request->season))
            ->latest('supply_date')
            ->paginate(20)
            ->withQueryString();

        $farmers = Farmer::where('is_active', true)->orderBy('name')->get();

        return view('water-entries.index', compact('entries', 'farmers'));
    }

    public function create()
    {
        $farmers = Farmer::where('is_active', true)->orderBy('name')->get();
        $defaultRate = optional(PumpOwner::first())->rate_per_hour ?? 0;
        return view('water-entries.create', compact('farmers', 'defaultRate'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'farmer_id'    => 'required|exists:farmers,id',
            'supply_date'  => 'required|date',
            'hours'        => 'required|numeric|min:0.5',
            'rate_per_hour'=> 'required|numeric|min:0',
            'season'       => 'nullable|string|max:50',
            'notes'        => 'nullable|string',
        ]);

        WaterEntry::create($data);
        return redirect()->route('water-entries.index')->with('success', 'পানি সরবরাহ এন্ট্রি যোগ করা হয়েছে।');
    }

    public function show(WaterEntry $waterEntry)
    {
        $waterEntry->load(['farmer', 'payments']);
        return view('water-entries.show', compact('waterEntry'));
    }

    public function edit(WaterEntry $waterEntry)
    {
        $farmers = Farmer::where('is_active', true)->orderBy('name')->get();
        return view('water-entries.edit', compact('waterEntry', 'farmers'));
    }

    public function update(Request $request, WaterEntry $waterEntry)
    {
        $data = $request->validate([
            'farmer_id'    => 'required|exists:farmers,id',
            'supply_date'  => 'required|date',
            'hours'        => 'required|numeric|min:0.5',
            'rate_per_hour'=> 'required|numeric|min:0',
            'season'       => 'nullable|string|max:50',
            'notes'        => 'nullable|string',
        ]);

        $waterEntry->update($data);
        return redirect()->route('water-entries.show', $waterEntry)->with('success', 'এন্ট্রি আপডেট হয়েছে।');
    }

    public function destroy(WaterEntry $waterEntry)
    {
        $waterEntry->delete();
        return redirect()->route('water-entries.index')->with('success', 'এন্ট্রি মুছে ফেলা হয়েছে।');
    }
}
