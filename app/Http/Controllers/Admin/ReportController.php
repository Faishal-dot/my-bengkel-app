<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Booking;

class ReportController extends Controller
{
   // ReportController.php

public function penghasilan()
{
    // Hapus withTrashed()
    $bookings = Booking::where('status', 'selesai')
        ->with(['service', 'user', 'mechanic']) 
        ->latest()
        ->get();

    $totalBooking = $bookings->sum(fn($b) => $b->service->price ?? 0);

    // Hapus withTrashed()
    $orders = Order::where('status', 'disetujui')
        ->with(['product', 'user'])
        ->latest()
        ->get();

    $totalOrder = $orders->sum('total_price');
    $total = $totalBooking + $totalOrder;

    return view('admin.reports.penghasilan', compact(
        'totalBooking', 'totalOrder', 'total', 'bookings', 'orders'
    ));
}
}