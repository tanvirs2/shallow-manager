<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index(Request $request)
    {
        $farmers = Farmer::withSum('waterEntries', 'total_amount')
            ->withSum('payments', 'amount')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('mobile', 'like', "%{$request->search}%")
                ->orWhere('village', 'like', "%{$request->search}%"))
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true))
            ->when($request->status === 'inactive', fn($q) => $q->where('is_active', false))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('farmers.index', compact('farmers'));
    }

    public function create()
    {
        return view('farmers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'mobile'           => 'required|string|max:20',
            'village'          => 'nullable|string|max:255',
            'union'            => 'nullable|string|max:255',
            'upazila'          => 'nullable|string|max:255',
            'land_area'        => 'required|numeric|min:0',
            'land_unit'        => 'required|in:acre,shotok,bigha',
            'land_description' => 'nullable|string',
            'nid'              => 'nullable|string|max:30',
            'notes'            => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');

        Farmer::create($data);
        return redirect()->route('farmers.index')->with('success', 'কৃষক সফলভাবে যোগ করা হয়েছে।');
    }

    public function show(Farmer $farmer)
    {
        $farmer->load(['waterEntries' => fn($q) => $q->latest(), 'payments' => fn($q) => $q->latest()]);
        return view('farmers.show', compact('farmer'));
    }

    public function edit(Farmer $farmer)
    {
        return view('farmers.edit', compact('farmer'));
    }

    public function update(Request $request, Farmer $farmer)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'mobile'           => 'required|string|max:20',
            'village'          => 'nullable|string|max:255',
            'union'            => 'nullable|string|max:255',
            'upazila'          => 'nullable|string|max:255',
            'land_area'        => 'required|numeric|min:0',
            'land_unit'        => 'required|in:acre,shotok,bigha',
            'land_description' => 'nullable|string',
            'nid'              => 'nullable|string|max:30',
            'notes'            => 'nullable|string',
        ]);

        $data['is_active'] = $request->has('is_active');

        $farmer->update($data);
        return redirect()->route('farmers.show', $farmer)->with('success', 'তথ্য আপডেট হয়েছে।');
    }

    public function destroy(Farmer $farmer)
    {
        $farmer->delete();
        return redirect()->route('farmers.index')->with('success', 'কৃষক মুছে ফেলা হয়েছে।');
    }
}
