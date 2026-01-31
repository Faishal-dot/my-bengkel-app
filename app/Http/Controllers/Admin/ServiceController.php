<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Product;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::query();

        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        $services = $query->with('products')->latest()->paginate(9);

        return view('admin.services.index', compact('services'));
    }


    public function create()
    {
        $products = Product::all();
        return view('admin.services.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'discount_start' => 'nullable|date',
            'discount_end'   => 'nullable|date',
            'products'       => 'nullable|array',
            'quantities'     => 'nullable|array',
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih murah dari harga asli.',
        ]);

        $service = Service::create($validated);

        if ($request->has('products')) {
            foreach ($request->products as $index => $productId) {
                if ($productId) {
                    $service->products()->attach($productId, [
                        'quantity' => $request->quantities[$index] ?? 1
                    ]);
                }
            }
        }

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil ditambahkan!');
    }


    public function edit(Service $service)
    {
        $products = Product::all();
        $service->load('products');

        return view('admin.services.edit', compact('service', 'products'));
    }


    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            // --- TAMBAHKAN INI ---
            'discount_start' => 'nullable|date',
            'discount_end'   => 'nullable|date',
            // ---------------------
            'products'       => 'nullable|array',
            'quantities'     => 'nullable|array',
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih murah dari harga asli.',
        ]);

        $service->update($validated);

        $syncData = [];
        if ($request->has('products')) {
            foreach ($request->products as $index => $productId) {
                if ($productId) {
                    $syncData[$productId] = [
                        'quantity' => $request->quantities[$index] ?? 1
                    ];
                }
            }
        }
        
        $service->products()->sync($syncData);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil diperbarui!');
    }


    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil dihapus!');
    }
}