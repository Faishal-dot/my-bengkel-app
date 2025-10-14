<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';
        $mechanics = Mechanic::when($q, fn($qry) => $qry->where('name','like',"%{$q}%"))
                             ->latest()->paginate(9);
        return view('admin.mechanics.index', compact('mechanics'));
    }

    public function create()
    {
        return view('admin.mechanics.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'specialization' => 'nullable|string|max:255',
        ]);

        Mechanic::create($data);

        return redirect()->route('admin.mechanics.index')->with('success','Mekanik berhasil ditambahkan.');
    }

    public function edit(Mechanic $mechanic)
    {
        return view('admin.mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'specialization' => 'nullable|string|max:255',
        ]);

        $mechanic->update($data);

        return redirect()->route('admin.mechanics.index')->with('success','Data mekanik berhasil diperbarui.');
    }

    public function destroy(Mechanic $mechanic)
    {
        $mechanic->delete();
        return redirect()->route('admin.mechanics.index')->with('success','Mekanik berhasil dihapus.');
    }
}