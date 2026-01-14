<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        // 1. Menambahkan filter where agar status 'unpaid' tidak muncul
        $payments = Payment::has('booking') 
            ->where('status', '!=', 'unpaid') // Tambahkan baris ini
            ->with(['booking.user', 'booking.service', 'booking.vehicle'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 ELSE 2 END") 
            ->latest()
            ->get();

        return view('admin.payment.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking' => function($q){
            $q->withTrashed();
        }, 'booking.user', 'booking.service', 'booking.vehicle']);
        
        return view('admin.payment.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        $payment->update([
            'status' => 'paid', 
        ]);

        $booking = $payment->booking;
        
        if ($booking) {
            $updateData = [
                'payment_status' => 'paid', 
            ];

            if (!in_array($booking->status, ['proses', 'selesai'])) {
                $updateData['status'] = 'disetujui';
            }

            $booking->update($updateData);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran berhasil dikonfirmasi. Status Booking: DISETUJUI.');
    }

    public function reject(Payment $payment)
    {
        $payment->update([
            'status' => 'rejected',
        ]);

        $booking = $payment->booking;
        
        if ($booking) {
            $booking->update([
                'payment_status' => 'failed' 
            ]);
        }

        return redirect()->route('admin.payments.index')
            ->with('success', 'Pembayaran telah ditolak.');
    }
}