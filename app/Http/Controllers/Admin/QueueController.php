<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil tanggal dari input, defaultnya hari ini
        $date = $request->input('date', now()->toDateString());

        // Ambil data berdasarkan tanggal tersebut dengan Eager Loading
        $queues = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
                    ->whereDate('booking_date', $date)
                    ->orderBy('queue_number', 'asc')
                    ->get();

        // Variabel $date dikirim ke view untuk mengisi value input date
        return view('admin.queue.index', compact('queues', 'date'));
    }
}