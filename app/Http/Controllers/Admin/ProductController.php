<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Tampilkan daftar produk dengan pencarian & pagination
    public function index(Request $request)
    {
        $query = Product::query();

        // Jika ada parameter q (pencarian)
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);

        // supaya kata kunci tetap terbawa saat pagination
        $products->appends($request->only('q'));

        return view('admin.products.index', compact('products'));
    }

    // Form tambah produk
    public function create()
    {
        return view('admin.products.create');
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    // Form edit produk
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    // Update produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil diperbarui.');
    }

    // Update stok produk
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return back()->with('success', 'Stok berhasil diperbarui!');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}