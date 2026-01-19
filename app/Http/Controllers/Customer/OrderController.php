<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Form pembelian
    public function create(Product $product)
    {
        return view('customer.order-create', compact('product'));
    }

    // Simpan pesanan & Buat Draft Pembayaran
    public function store(Request $request, Product $product)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    if ($product->stock < $request->quantity) {
        return redirect()->back()->with('error', 'Stok tidak mencukupi.');
    }

    $total = $product->price * $request->quantity;

    // Simpan variabel order di luar agar bisa diakses setelah transaksi
    $order = DB::transaction(function () use ($request, $product, $total) {
        $newOrder = Order::create([
            'user_id'     => auth()->id(),
            'product_id'  => $product->id,
            'quantity'    => $request->quantity,
            'total_price' => $total,
            'status'      => 'pending',
            'payment_status' => 'unpaid',
        ]);

        Payment::create([
            'user_id'    => auth()->id(), // Tambahkan user_id agar query payment lebih mudah
            'order_id'   => $newOrder->id,
            'amount'     => $total,
            'status'     => 'unpaid',
        ]);

        return $newOrder; // Mengembalikan data order yang baru dibuat
    });

    // UBAH DI SINI: Langsung arahkan ke route pembayaran produk
    return redirect()->route('customer.payment.product', $order->id)
        ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
}

    // Daftar pesanan customer
    public function index()
    {
        // Load relasi product dan payment agar bisa cek status pembayaran di blade
        $orders = Order::with(['product', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }
}