<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:6|confirmed',
            'ktp'            => 'required|string|max:20',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string',
            'specialization' => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat akun user
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'mechanic',
            ]);

            // 2. Buat data mekanik
            Mechanic::create([
                'user_id'        => $user->id,
                'name'           => $request->name,
                'ktp'            => $request->ktp,
                'phone'          => $request->phone,
                'address'        => $request->address,
                'specialization' => $request->specialization,
            ]);
        });

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
            'ktp'            => 'required|max:20',
            'phone'          => 'nullable|max:30',
            'address'        => 'nullable',
            'specialization' => 'nullable|max:255',
        ]);

        DB::transaction(function () use ($data, $mechanic) {
            $mechanic->update($data);
            
            if ($mechanic->user) {
                $mechanic->user->update(['name' => $data['name']]);
            }
        });

        return redirect()
            ->route('admin.mechanics.index')
            ->with('success', 'Data mekanik berhasil diperbarui.');
    }

    public function destroy(Mechanic $mechanic)
    {
        try {
            DB::transaction(function () use ($mechanic) {
                $user = $mechanic->user;

                if ($user) {
                    // Hapus data di tabel chat_messages terlebih dahulu (Solusi Error Constraint)
                    DB::table('chat_messages')->where('user_id', $user->id)->delete();
                    
                    // Hapus user (ini otomatis akan menghapus mekanik jika di migration diset cascade)
                    // Jika tidak cascade, kita hapus manual dua-duanya
                    $mechanic->delete();
                    $user->delete();
                } else {
                    $mechanic->delete();
                }
            });

            return redirect()
                ->route('admin.mechanics.index')
                ->with('success', 'Mekanik dan riwayat chat berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()
                ->route('admin.mechanics.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}