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
        // Ambil semua booking yang statusnya selesai
        $bookings = Booking::where('status', 'selesai')
            ->with(['service', 'user', 'mechanic'])
            ->get();

        // Hitung total harga layanan dari booking yang selesai
        $totalBooking = $bookings->sum(fn($b) => $b->service->price ?? 0);

        // Ambil semua order produk yang disetujui
        $orders = Order::where('status', 'disetujui')
            ->with(['product', 'user'])
            ->get();

        // Hitung total harga order produk
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