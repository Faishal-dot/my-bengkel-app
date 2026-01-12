<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingQueueController extends Controller
{
    /**
     * Menampilkan antrian booking berdasarkan tanggal
     */
    public function index(Request $request)
    {
        // Ambil tanggal dari query (?date=xxxx)
        $date = $request->date ?? now()->toDateString();

        // Jika format tanggal tidak valid → pakai hari ini
        if (!strtotime($date)) {
            $date = now()->toDateString();
        }

        // Jika tanggal sudah lewat → paksa kembali ke hari ini
        if ($date < now()->toDateString()) {
            $date = now()->toDateString();
        }

        // Ambil semua booking hari itu berdasarkan queue_number
        $queues = Booking::with(['user', 'service', 'mechanic'])
            ->whereDate('booking_date', $date)
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('customer.booking-queue.index', [
            'queues'       => $queues,
            'selectedDate' => $date,
        ]);
    }
}