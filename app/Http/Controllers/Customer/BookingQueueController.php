<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingQueueController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal dari GET, jika kosong gunakan hari ini
        $date = $request->input('date', now()->toDateString());

        $queues = Booking::whereDate('booking_date', $date)
            ->orderBy('created_at')
            ->get();

        return view('customer.booking-queue.index', [
            'queues' => $queues,
            'selectedDate' => $date,
        ]);
    }
}