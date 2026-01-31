<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // 1. TAMPILAN LIST PEMBAYARAN
    public function index()
    {
        // Simpan waktu pengecekan (opsional untuk notifikasi)
        session(['last_checked_payments' => now()]);

        $payments = Payment::where('status', '!=', 'unpaid')
            ->whereNotNull('proof')
            ->with(['booking.user', 'booking.service', 'order.user'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END") 
            ->latest()
            ->get();

        return view('admin.payment.index', compact('payments'));
    }

    // 2. DETAIL PEMBAYARAN (Agar tidak error saat klik tombol view)
    public function show(Payment $payment)
    {
        $payment->load([
            'booking' => function($q){
                $q->withTrashed();
            }, 
            'booking.user', 'booking.service', 'booking.vehicle',
            'order.user', 'order.product' 
        ]);
        
        return view('admin.payment.show', compact('payment'));
    }

    // 3. KONFIRMASI PEMBAYARAN (Sikat Stok)
    public function confirm(Payment $payment)
    {
        $payment->update(['status' => 'paid']);

        // Jika Transaksi Servis (Booking)
        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'paid']);
            
            // Potong Stok Produk yang ada di dalam bundle servis (pivot table)
            $service = $payment->booking->service;
            if ($service && $service->products) {
                foreach ($service->products as $product) {
                    $qty = $product->pivot->quantity ?? 1;
                    if ($product->stock >= $qty) {
                        $product->decrement('stock', $qty);
                    }
                }
            }
        }

        // Jika Transaksi Produk Satuan (Order)
        if ($payment->order) {
            $payment->order->update([
                'payment_status' => 'paid', 
                'status' => 'diproses'
            ]);
            
            $product = $payment->order->product;
            if ($product && $product->stock >= $payment->order->quantity) {
                $product->decrement('stock', $payment->order->quantity);
            }
        }

        return redirect()->route('admin.payments.index')->with('success', 'Pembayaran Dikonfirmasi & Stok Berkurang.');
    }

    // 4. TOLAK PEMBAYARAN
    public function reject(Payment $payment)
    {
        $payment->update(['status' => 'failed']);

        if ($payment->booking) {
            $payment->booking->update(['payment_status' => 'unpaid']);
        }

        if ($payment->order) {
            $payment->order->update(['payment_status' => 'unpaid']);
        }

        return redirect()->route('admin.payments.index')->with('warning', 'Pembayaran Ditolak.');
    }

    // 5. FUNGSI PRINT (SOLUSI ERROR INTERNAL SERVER ERROR ANDA)
    public function print($id)
    {
        $payment = Payment::with([
            'booking.service', 
            'booking.vehicle', 
            'order.product',
            'booking.user', 
            'order.user'
        ])->findOrFail($id);

        $status = strtolower($payment->status);
        
        // Hanya transaksi lunas yang bisa dicetak
        if (!in_array($status, ['paid', 'approved', 'success', 'lunas'])) {
            return back()->with('error', 'Nota hanya bisa dicetak untuk transaksi yang sudah lunas.');
        }

        return view('admin.payment.print', compact('payment'));
    }
}