<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Tampilkan semua layanan (dengan fitur pencarian)
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Jika ada keyword pencarian
        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Pagination + urut terbaru
        $services = $query->latest()->paginate(9);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Form tambah layanan
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Simpan layanan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Form edit layanan
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update layanan
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'price'       => 'required|numeric|min:0',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Hapus layanan
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil dihapus!');
    }
}