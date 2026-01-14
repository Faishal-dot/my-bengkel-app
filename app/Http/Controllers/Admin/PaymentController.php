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
    // 1. Update status di tabel payments
    $payment->update([
        'status' => 'paid', 
    ]);

    // 2. Ambil booking terkait
    $booking = $payment->booking;
    
    if ($booking) {
        $updateData = [
            'payment_status' => 'paid', 
        ];

        // Pastikan status hanya berubah ke 'disetujui' jika sebelumnya masih 'menunggu'
        if ($booking->status === 'menunggu') {
            $updateData['status'] = 'disetujui';
        }

        $booking->update($updateData);
    }

    return redirect()->route('admin.payments.index')
        ->with('success', 'Pembayaran berhasil dikonfirmasi. Status Booking diperbarui!');
}

    public function reject(Payment $payment)
{
    // 1. Update status di tabel payments ke 'failed'
    // (Pastikan ENUM status di tabel payments sudah mendukung 'failed')
    $payment->update([
        'status' => 'failed',
    ]);

    $booking = $payment->booking;
    
    if ($booking) {
        // 2. Update status di tabel bookings
        // Ubah 'failed' menjadi 'unpaid' agar tidak bentrok dengan batasan ENUM DB
        $booking->update([
            'payment_status' => 'unpaid' 
        ]);
    }

    return redirect()->route('admin.payments.index')
        ->with('success', 'Pembayaran telah ditolak.');
}
}