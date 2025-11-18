<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q ?? '';

        $mechanics = Mechanic::with('user')
            ->when($q, fn($qry) => $qry->where('name', 'like', "%{$q}%"))
            ->latest()
            ->paginate(9);

        return view('admin.mechanics.index', compact('mechanics'));
    }

    public function create()
    {
        return view('admin.mechanics.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'phone'    => 'nullable|string|max:20',
        'specialization' => 'nullable|string|max:100',
    ]);

    // 1. Buat akun user
    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'mechanic',
    ]);

    // 2. Buat data mekanik (supaya muncul di tabel admin/mechanics)
    Mechanic::create([
        'user_id' => $user->id,
        'name' => $request->name,
        'phone' => $request->phone,
        'specialization' => $request->specialization,
    ]);

    return redirect()
        ->route('admin.mechanics.index')
        ->with('success', 'Akun mekanik berhasil dibuat!');
}

    public function edit(Mechanic $mechanic)
    {
        return view('admin.mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $data = $request->validate([
            'name'           => 'required|max:255',
            'phone'          => 'nullable|max:30',
            'specialization' => 'nullable|max:255',
        ]);

        $mechanic->update($data);
        $mechanic->user->update(['name' => $data['name']]);

        return redirect()
            ->route('admin.mechanics.index')
            ->with('success', 'Data mekanik berhasil diperbarui.');
    }

    public function destroy(Mechanic $mechanic)
    {
        // Hapus user + mekanik
        $mechanic->user->delete();
        $mechanic->delete();

        return redirect()
            ->route('admin.mechanics.index')
            ->with('success', 'Mekanik berhasil dihapus.');
    }
}