<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function index()
    {
        // Mengambil payment dengan relasi
        $payments = Payment::with(['booking.user', 'booking.service', 'booking.vehicle'])
            ->orderBy('status', 'asc') // Menampilkan pending di paling atas
            ->latest()
            ->get();

        // PASTIKAN FOLDERNYA 'admin.payment' (singular)
        return view('admin.payment.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['booking.user', 'booking.service', 'booking.vehicle']);
        // PASTIKAN FOLDERNYA 'admin.payment' (singular)
        return view('admin.payment.show', compact('payment'));
    }

    public function confirm(Payment $payment)
    {
        // 1. Update Status di Tabel Payments menjadi LUNAS (PAID)
        $payment->update([
            'status' => 'paid', 
        ]);

        // 2. Update Status di Tabel Bookings
        $booking = $payment->booking;
        
        if ($booking) {
            $updateData = [
                'payment_status' => 'paid', 
            ];

            // Cek status saat ini agar tidak menimpa jika sudah diproses mekanik
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
        // 1. Update Status Payment jadi rejected
        $payment->update([
            'status' => 'rejected',
        ]);

        // 2. Update Booking: Kembalikan payment_status ke failed
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