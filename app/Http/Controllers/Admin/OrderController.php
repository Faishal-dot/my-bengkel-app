<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Notifications\OrderStatusUpdated; // ✅ notifikasi

class OrderController extends Controller
{
    // ✅ Tampilkan semua pesanan
    public function index()
    {
        $orders = Order::with(['user', 'product'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    // ✅ Update status pesanan (untuk form biasa)
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);

        $order->update(['status' => $request->status]);

        // ✅ Kirim notifikasi ke customer
        if ($order->user) {
            $order->user->notify(new OrderStatusUpdated($order));
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui & notifikasi dikirim.');
    }

    // ✅ Update status pesanan via AJAX
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak'
        ]);

        $order->update(['status' => $request->status]);

        if($order->user){
            $order->user->notify(new OrderStatusUpdated($order));
        }

        // Jika request AJAX, kembalikan JSON
        if($request->ajax()){
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui & notifikasi dikirim.');
    }

    // ✅ Detail pesanan
    public function show($id)
    {
        $order = Order::with(['user', 'product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function destroy($id)
    {
    $order = Order::findOrFail($id);
    $order->delete();

    return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }

}