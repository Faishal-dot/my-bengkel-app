<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Booking;

class ReportController extends Controller
{
    public function penghasilan()
    {
        // Ambil semua booking yang approved
        $bookings = Booking::where('status', 'approved')->with('service')->get();
        $totalBooking = $bookings->sum(fn($b) => $b->service->price);

        // Ambil semua order produk yang disetujui
        $orders = Order::where('status', 'disetujui')->with('product','user')->get();
        $totalOrder = $orders->sum('total_price');

        // Total keseluruhan
        $total = $totalBooking + $totalOrder;

        return view('admin.reports.penghasilan', compact(
            'totalBooking',
            'totalOrder',
            'total',
            'bookings',
            'orders'
        ));
    }
}
