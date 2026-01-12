<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk
     * - Dengan pagination (normal view)
     * - Dengan pencarian
     * - Dengan list lengkap (untuk slide detail)
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Jika ada pencarian
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Pagination normal
        $products = $query->latest()->paginate(10);

        // Supaya kata kunci tetap terbawa ke pagination
        $products->appends($request->only('q'));

        // Semua produk (tanpa pagination) untuk DETAIL SLIDE
        $productsAll = Product::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'productsAll'));
    }

    /**
     * Form tambah produk
     */
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $request->image,
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk
     */
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->update([
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

    /**
     * Update stok
     */
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

    /**
     * Hapus produk
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil dihapus.');
    }
}
