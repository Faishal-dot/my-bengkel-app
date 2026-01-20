<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Tampilkan daftar produk di dashboard Admin
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // PENCARIAN: Nama, Deskripsi, dan SKU
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10);
        $products->appends($request->only('search'));

        // Digunakan untuk komponen lain jika diperlukan (misal: modal/slide)
        $productsAll = Product::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'productsAll'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Simpan produk baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku',
            'name' => 'required|string|max:255',
            'category' => 'required|in:Sparepart,Cairan', // Memastikan kategori valid
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'purchase_price' => $request->purchase_price,
            'price' => $request->price,
            'stock' => $request->stock ?? 0,
            'image' => $request->image, // Jika Anda punya sistem upload, proses filenya di sini
        ]);

        return redirect()->route('admin.products.index')
                         ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update produk (PERBAIKAN: Menambahkan kategori)
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'category' => 'required|in:Sparepart,Cairan', // Validasi kategori ditambahkan
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        // Perbaikan: Pastikan 'category' masuk ke dalam array update
        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'category' => $request->category, // Baris ini yang sebelumnya hilang
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