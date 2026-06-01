<?php

namespace App\Http\Controllers;

use App\Models\PumpOwner;
use Illuminate\Http\Request;

class PumpOwnerController extends Controller
{
    public function edit()
    {
        $owner = PumpOwner::first() ?? new PumpOwner();
        return view('pump-owner.edit', compact('owner'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'mobile'        => 'required|string|max:20',
            'pump_name'     => 'nullable|string|max:255',
            'village'       => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:500',
            'rate_per_hour' => 'required|numeric|min:0',
            'nid'           => 'nullable|string|max:30',
            'notes'         => 'nullable|string',
        ]);

        $owner = PumpOwner::first();
        if ($owner) {
            $owner->update($data);
        } else {
            PumpOwner::create($data);
        }

        return redirect()->route('pump-owner.edit')->with('success', 'প্রোফাইল সফলভাবে আপডেট হয়েছে।');
    }
}
