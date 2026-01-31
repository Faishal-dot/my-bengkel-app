<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\OrderDetail; // Pastikan Model ini diimport
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
        // 1. CEK APAKAH ADA DATA DI KERANJANG (CART)
        $cart = session()->get('cart', []);

        if (!empty($cart)) {
            // JIKA CHECKOUT DARI KERANJANG
            return DB::transaction(function () use ($cart) {
                $totalAll = 0;
                
                // Hitung total semua produk di keranjang
                foreach ($cart as $item) {
                    $totalAll += $item['price'] * $item['quantity'];
                }

                // Buat Order Utama
                $order = Order::create([
                    'user_id'        => auth()->id(),
                    'product_id'     => array_key_first($cart), 
                    'quantity'       => count($cart),
                    'total_price'    => $totalAll,
                    'status'         => 'pending',
                    'payment_status' => 'unpaid',
                ]);

                // SIMPAN RINCIAN KE TABLE order_details
                foreach ($cart as $item) {
                    OrderDetail::create([
                        'order_id'   => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity'   => $item['quantity'],
                        'price'      => $item['price'],
                    ]);

                    // Update stok produk
                    $prod = Product::find($item['product_id']);
                    if ($prod) {
                        $prod->decrement('stock', $item['quantity']);
                    }
                }

                Payment::create([
                    'user_id'  => auth()->id(),
                    'order_id' => $order->id,
                    'amount'   => $totalAll,
                    'status'   => 'unpaid',
                ]);

                // Kosongkan keranjang setelah berhasil
                session()->forget('cart');

                return redirect()->route('customer.payment.product', $order->id)
                    ->with('success', 'Pesanan dari keranjang berhasil dibuat!');
            });
        }

        // 2. JIKA BELI LANGSUNG (TANPA KERANJANG)
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($product->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi.');
        }

        $total = $product->price * $request->quantity;

        $order = DB::transaction(function () use ($request, $product, $total) {
            $newOrder = Order::create([
                'user_id'        => auth()->id(),
                'product_id'     => $product->id,
                'quantity'       => $request->quantity,
                'total_price'    => $total,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Tetap simpan ke order_details agar tampilan rincian pembayaran konsisten
            OrderDetail::create([
                'order_id'   => $newOrder->id,
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
                'price'      => $product->price,
            ]);

            Payment::create([
                'user_id'  => auth()->id(),
                'order_id' => $newOrder->id,
                'amount'   => $total,
                'status'   => 'unpaid',
            ]);

            $product->decrement('stock', $request->quantity);

            return $newOrder;
        });

        return redirect()->route('customer.payment.product', $order->id)
            ->with('success', 'Pesanan berhasil dibuat! Silakan upload bukti pembayaran.');
    }

    // Daftar pesanan customer
    public function index()
    {
        $orders = Order::with(['product', 'payment', 'orderDetails.product'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('customer.orders', compact('orders'));
    }
}