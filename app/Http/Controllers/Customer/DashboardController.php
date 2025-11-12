<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Hitung total data
    $totalBookings = Booking::where('user_id', $user->id)->count();
    $totalOrders = Order::where('user_id', $user->id)->count();
    $totalServices = Service::count();

    // Ambil 5 booking terakhir
    $recentBookings = Booking::where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get()
        ->map(fn($b) => [
            'description' => "Booking #{$b->id} (" . ($b->vehicle->name ?? 'Kendaraan') . ") dibuat",
            'created_at' => $b->created_at,
        ]);

    // Ambil 5 order terakhir
    $recentOrders = Order::where('user_id', $user->id)
        ->latest()
        ->take(5)
        ->get()
        ->map(fn($o) => [
            'description' => "Order #{$o->id} (" . ($o->product->name ?? 'Produk') . ") dibuat",
            'created_at' => $o->created_at,
        ]);

    // ✅ Gunakan Support Collection agar aman
    $recentActivities = collect($recentBookings)
        ->merge(collect($recentOrders))
        ->sortByDesc('created_at')
        ->take(5)
        ->values(); // reset index

    return view('dashboard', compact(
        'totalBookings',
        'totalOrders',
        'totalServices',
        'recentActivities'
    ));
}
}