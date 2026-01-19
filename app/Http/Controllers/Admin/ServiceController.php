<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Product;
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

        /** * Eager Loading 'products' ditambahkan agar badge "Paket Bundle" 
         * bisa mendeteksi jumlah produk tanpa query berulang (N+1 Problem)
         */
        $services = $query->with('products')->latest()->paginate(9);

        return view('admin.services.index', compact('services'));
    }

    /**
     * Form tambah layanan
     */
    public function create()
    {
        // Ambil data produk untuk ditampilkan di dropdown form
        $products = Product::all();
        return view('admin.services.create', compact('products'));
    }

    /**
     * Simpan layanan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'products'       => 'nullable|array',
            'quantities'     => 'nullable|array',
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih murah dari harga asli.',
        ]);

        // 1. Simpan Data Layanan
        $service = Service::create($validated);

        // 2. Simpan Relasi ke Tabel Pivot
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

    /**
     * Form edit layanan
     */
    public function edit(Service $service)
    {
        // Ambil daftar produk untuk dropdown
        $products = Product::all();
        
        // Load relasi produk agar bisa tampil di form edit
        $service->load('products');

        return view('admin.services.edit', compact('service', 'products'));
    }

    /**
     * Update layanan
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'description'    => 'nullable|string|max:255',
            'price'          => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'products'       => 'nullable|array',
            'quantities'     => 'nullable|array',
        ], [
            'discount_price.lt' => 'Harga diskon harus lebih murah dari harga asli.',
        ]);

        // 1. Update data dasar
        $service->update($validated);

        // 2. Update relasi produk menggunakan sync()
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
        
        // Menghapus yang lama dan mengganti dengan yang baru dari form secara otomatis
        $service->products()->sync($syncData);

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil diperbarui!');
    }

    /**
     * Hapus layanan
     */
    public function destroy(Service $service)
    {
        // Relasi di tabel pivot akan terhapus otomatis karena onDelete('cascade') di migration
        $service->delete();

        return redirect()->route('admin.services.index')
                         ->with('success', 'Layanan berhasil dihapus!');
    }
}