<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Form pembelian
    public function create(Product $product)
    {
        // ✅ arahkan ke resources/views/customer/order-create.blade.php
        return view('customer.order-create', compact('product'));
    }

    // Simpan pesanan
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // cek stok dulu
        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $total = $product->price * $request->quantity;

        // kurangi stok produk
        $product->decrement('stock', $request->quantity);

        // buat pesanan
        Order::create([
            'user_id'     => auth()->id(),
            'product_id'  => $product->id,
            'quantity'    => $request->quantity,
            'total_price' => $total,
            'status'      => 'pending',
        ]);

        // ✅ arahkan ke resources/views/customer/orders.blade.php
        return redirect()->route('customer.orders.index')
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    // Daftar pesanan customer
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();

        // ✅ arahkan ke resources/views/customer/orders.blade.php
        return view('customer.orders', compact('orders'));
    }
}