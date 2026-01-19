<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\DB; // ✅ Tambahkan ini

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'product', 'service.products'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);

        // ✅ Gunakan Database Transaction agar aman
        DB::transaction(function () use ($request, $order) {
            
            // Cek jika status berubah menjadi 'disetujui' dan stok belum dipotong
            if ($request->status == 'disetujui' && $order->status != 'disetujui') {
                
                // 1. Cek apakah ini pesanan LAYANAN (Service)
                // Pastikan di model Order Anda punya relasi 'service'
                if ($order->service_id) {
                    $service = $order->service()->with('products')->first();

                    if ($service && $service->products->count() > 0) {
                        foreach ($service->products as $product) {
                            // Ambil jumlah yang harus dikurangi dari tabel pivot
                            $qtyToReduce = $product->pivot->quantity;

                            // Kurangi stok produk
                            $product->decrement('stock', $qtyToReduce);
                        }
                    }
                }
            }

            // Update status pesanan
            $order->update(['status' => $request->status]);
        });

        if ($order->user) {
            $order->user->notify(new OrderStatusUpdated($order));
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui & stok telah disesuaikan.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);

        DB::transaction(function () use ($request, $order) {
            if ($request->status == 'disetujui' && $order->status != 'disetujui') {
                if ($order->service_id) {
                    $service = $order->service()->with('products')->first();
                    if ($service && $service->products->count() > 0) {
                        foreach ($service->products as $product) {
                            $product->decrement('stock', $product->pivot->quantity);
                        }
                    }
                }
            }
            $order->update(['status' => $request->status]);
        });

        if($order->user){
            $order->user->notify(new OrderStatusUpdated($order));
        }

        if($request->ajax()){
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status diperbarui.');
    }

    public function show($id)
    {
        $order = Order::with(['user', 'product', 'service.products'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }
}