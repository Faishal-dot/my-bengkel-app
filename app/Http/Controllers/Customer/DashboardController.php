<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Service;
use App\Models\Product; // Pastikan Model Product di-import
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Hitung total data
        // Total Booking milik customer yang login
        $totalBookings = Booking::where('user_id', $user->id)->count();
        
        // PERBAIKAN: Menghitung TOTAL SEMUA PRODUK yang ditambahkan oleh Admin
        $totalProducts = Product::count(); 
        
        // Total Layanan yang tersedia
        $totalServices = Service::count();

        // 2. Ambil 5 booking terakhir customer
        $recentBookings = Booking::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($b) => [
                'description' => "Booking #{$b->id} (" . ($b->vehicle->name ?? 'Kendaraan') . ") dibuat",
                'created_at' => $b->created_at,
            ]);

        // 3. Ambil 5 order terakhir customer
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn($o) => [
                'description' => "Order #{$o->id} (" . ($o->product->name ?? 'Produk') . ") dibuat",
                'created_at' => $o->created_at,
            ]);

        // 4. Gabungkan aktivitas agar muncul di tabel aktivitas terbaru
        $recentActivities = collect($recentBookings)
            ->merge(collect($recentOrders))
            ->sortByDesc('created_at')
            ->take(5)
            ->values();

        // Kirim data ke view dashboard
        return view('dashboard', compact(
            'totalBookings',
            'totalProducts',
            'totalServices',
            'recentActivities'
        ));
    }
}