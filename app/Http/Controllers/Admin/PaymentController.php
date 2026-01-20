<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
{
    // Simpan waktu sekarang ke session saat admin buka halaman ini
    session(['last_checked_payments' => now()]);

    $payments = Payment::where('status', '!=', 'unpaid')
        ->whereNotNull('proof')
        ->with(['booking.user', 'order.user'])
        ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END") 
        ->latest()
        ->get();

    return view('admin.payment.index', compact('payments'));
}

    public function show(Payment $payment)
    {
        $payment->load([
            'booking' => function($q){
                $q->withTrashed();
            }, 
            'booking.user', 'booking.service', 'booking.vehicle',
            'order.user', 'order.product' // Tambahan untuk produk
        ]);
        
        return view('admin.payment.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        // 1. Update status di tabel payments menjadi 'paid'
        $payment->update([
            'status' => 'paid', 
        ]);

        // === LOGIKA UNTUK BOOKING (SERVIS) ===
        $booking = $payment->booking;
        if ($booking) {
            $updateData = ['payment_status' => 'paid'];

            if ($booking->status === 'menunggu') {
                $updateData['status'] = 'disetujui';
            }

            $booking->update($updateData);

            // Pengurangan stok bundle servis
            $service = $booking->service;
            if ($service && $service->products()->exists()) {
                foreach ($service->products as $product) {
                    $quantityUsed = $product->pivot->quantity ?? 1;
                    if ($product->stock >= $quantityUsed) {
                        $product->decrement('stock', $quantityUsed);
                    }
                }
            }
        }

        // === LOGIKA UNTUK ORDER (PRODUK) ===
        $order = $payment->order;
        if ($order) {
            // Update status pembayaran di tabel orders
            $order->update([
                'payment_status' => 'paid',
                'status' => 'diproses' // Opsional: otomatis ubah status order
            ]);

            // Pengurangan stok produk satuan
            $product = $order->product;
            if ($product && $product->stock >= $order->quantity) {
                $product->decrement('stock', $order->quantity);
            }
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function reject(Payment $payment)
    {
        $payment->update(['status' => 'failed']);

        // Rejection untuk Booking
        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'unpaid']);
        }

        // Rejection untuk Order Produk
        if ($payment->order) {
            $payment->order->update(['payment_status' => 'unpaid']);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran telah ditolak.');
    }

    public function print($id)
{
    // Cari data pembayaran dengan relasi lengkap
    $payment = Payment::with([
        'booking.service', 
        'booking.vehicle', 
        'order.product',
        'booking.user', 
        'order.user'
    ])->findOrFail($id);

    // Cek apakah status sudah lunas
    $status = strtolower($payment->status);
    if (!in_array($status, ['paid', 'approved', 'lunas', 'success'])) {
        return back()->with('error', 'Nota hanya bisa dicetak untuk transaksi yang sudah lunas.');
    }

    // Arahkan ke view nota yang sudah ada
    return view('admin.payment.print', compact('payment'));
}
}