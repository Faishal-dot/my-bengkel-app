<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Booking;
use App\Models\Product;
use App\Models\Service;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts   = Product::count();
        $totalOrders     = Order::count();
        $totalServices   = Service::count();

        // Hitung customer unik berdasarkan order + booking
        $customerOrders   = Order::pluck('user_id')->toArray();
        $customerBookings = Booking::pluck('user_id')->toArray();

        $totalCustomers = collect($customerOrders)
                            ->merge($customerBookings)
                            ->unique()
                            ->count();

        // --- BAGIAN PERBAIKAN: MENGGABUNGKAN AKTIVITAS DENGAN DETAIL ---
        
        // 1. Ambil Order Terbaru + Info Produk
        $orders = Order::with('user')->latest()->take(5)->get()->map(function($order) {
            // Mengambil nama produk dari kolom product_name
            $detail = $order->product_name ?? 'Produk';
            return [
                'created_at'  => $order->created_at,
                'description' => "Order #{$order->id} ({$detail}) dibuat oleh " . ($order->user->name ?? 'User')
            ];
        });

        // 2. Ambil Booking Terbaru + Info Kendaraan
        $bookings = Booking::with('user')->latest()->take(5)->get()->map(function($booking) {
            // Mengambil nama kendaraan dari kolom vehicle_name
            $detail = $booking->vehicle_name ?? 'Kendaraan';
            return [
                'created_at'  => $booking->created_at,
                'description' => "Booking #{$booking->id} ({$detail}) dibuat oleh " . ($booking->user->name ?? 'User')
            ];
        });

        // 3. Gabungkan, Urutkan berdasarkan waktu terbaru, dan ambil 5 data teratas
        $recentActivities = $orders->concat($bookings)
                                   ->sortByDesc('created_at')
                                   ->values()
                                   ->take(5);

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalServices',
            'recentActivities'
        ));
    }
}