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
        // 1. Ambil data Booking (Service) yang sudah selesai
        $bookings = Booking::where('status', 'selesai')
            ->with(['service', 'user', 'mechanic']) 
            ->latest()
            ->get();

        $totalBooking = $bookings->sum(fn($b) => $b->service->price ?? 0);

        // 2. Ambil data Order (Produk)
        // Perbaikan: Menambahkan status 'diproses' agar data yang Anda test muncul
        $orders = Order::whereIn('status', ['disetujui', 'selesai', 'diproses'])
            ->with(['product', 'user'])
            ->latest()
            ->get();

        $totalOrder = $orders->sum('total_price');
        
        // 3. Hitung Total Gabungan
        $total = $totalBooking + $totalOrder;

        return view('admin.reports.penghasilan', compact(
            'totalBooking', 'totalOrder', 'total', 'bookings', 'orders'
        ));
    }
}