<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = auth()->user()->vehicles()->latest()->paginate(10);
        return view('customer.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('customer.vehicles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'color'        => 'required|string|max:100',
            'year'         => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        auth()->user()->vehicles()->create($request->all());

        return redirect()->route('customer.vehicles.index')
                         ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    // --- LOGIC EDIT TAMBAHAN ---
    public function edit(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== auth()->id()) {
            abort(403);
        }
        return view('customer.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number,' . $vehicle->id,
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'color'        => 'required|string|max:100',
            'year'         => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $vehicle->update($request->all());

        return redirect()->route('customer.vehicles.index')
                         ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->user_id !== auth()->id()) {
            abort(403);
        }
        $vehicle->delete();
        return back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}