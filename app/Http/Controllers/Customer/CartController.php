<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Menampilkan halaman keranjang belanja
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // PERBAIKAN: Menggunakan paginate agar variabel $products di view 
        // tidak menyebabkan error saat memanggil method links() atau withQueryString()
        $products = Product::where('stock', '>', 0)->latest()->paginate(3);

        return view('customer.cart.index', compact('cart', 'products'));
    }

    // Menambah produk ke keranjang
    public function add(Product $product)
    {
        $cart = session()->get('cart', []);

        // Cek jika produk sudah ada di keranjang
        if(isset($cart[$product->id])) {
            // Cek apakah penambahan quantity melebihi stok yang ada
            if ($cart[$product->id]['quantity'] + 1 > $product->stock) {
                return redirect()->back()->with('error', 'Gagal menambah! Stok produk tidak mencukupi.');
            }
            $cart[$product->id]['quantity']++;
        } else {
            // Jika produk baru, masukkan ke array session
            $cart[$product->id] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "description" => $product->description,
            ];
        }

        session()->put('cart', $cart);

        // Menggunakan redirect()->back() agar tetap di halaman saat ini (misal: daftar produk)
        return redirect()->back()->with('success', 'Produk ' . $product->name . ' berhasil ditambahkan ke keranjang!');
    }

    // Update jumlah produk (Quantity)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []); // Berikan default array kosong

        if(isset($cart[$id])) {
            // Validasi input: harus angka dan minimal 1
            $request->validate([
                'quantity' => 'required|numeric|min:1'
            ]);

            if($request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok terbatas! Hanya tersedia ' . $product->stock . ' item.');
            }

            $cart[$id]['quantity'] = (int)$request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
        }
        
        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    // Menghapus item dari keranjang
    public function remove($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }
}