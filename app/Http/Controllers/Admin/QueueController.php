<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $queues = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->whereDate('booking_date', $date)
            ->orderBy('queue_number', 'ASC')
            ->get();

        return view('admin.queue.index', compact('queues'));
    }
}