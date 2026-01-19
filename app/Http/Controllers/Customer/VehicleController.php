<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Daftar kendaraan user
    public function index()
    {
        $vehicles = auth()->user()->vehicles()->latest()->paginate(10);
        return view('customer.vehicles.index', compact('vehicles'));
    }

    // Form tambah kendaraan
    public function create()
    {
        return view('customer.vehicles.create');
    }

    // Simpan kendaraan baru
    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255|unique:vehicles,plate_number',
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'color'        => 'required|string|max:100', // Validasi kolom warna
            'year'         => 'required|digits:4|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        // Karena menggunakan $request->all(), pastikan 'color' ada di $fillable Model Vehicle
        auth()->user()->vehicles()->create($request->all());

        return redirect()->route('customer.vehicles.index')
                         ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    // Hapus kendaraan
    public function destroy(Vehicle $vehicle)
    {
        // Pastikan hanya pemilik yang bisa menghapus
        if ($vehicle->user_id !== auth()->id()) {
            abort(403);
        }

        $vehicle->delete();

        return back()->with('success', 'Kendaraan berhasil dihapus.');
    }
}