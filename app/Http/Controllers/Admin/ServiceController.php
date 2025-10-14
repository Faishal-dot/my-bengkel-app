<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil ditambahkan');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $service->update($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil diperbarui');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Layanan berhasil dihapus');
    }
}