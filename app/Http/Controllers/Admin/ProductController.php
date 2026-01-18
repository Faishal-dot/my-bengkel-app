<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // PENCARIAN: mendukung Nama, Deskripsi, dan SKU (Kode Produk)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%") // Tambahan search by SKU
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination normal
        $products = $query->latest()->paginate(10);

        // Agar keyword search tidak hilang saat pindah halaman pagination
        $products->appends($request->only('search'));

        // Semua produk untuk DETAIL SLIDE
        $productsAll = Product::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'productsAll'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Simpan produk baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku', // Validasi Kode Produk Unik
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        Product::create([
            'sku' => $request->sku, // Simpan SKU
            'name' => $request->name,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'image' => $request->image,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update produk
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id, // Abaikan SKU milik sendiri saat update
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->update([
            'sku' => $request->sku, // Update SKU
            'name' => $request->name,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $request->image,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update([
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}