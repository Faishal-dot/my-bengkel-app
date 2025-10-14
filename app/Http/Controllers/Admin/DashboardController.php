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
        $customerOrders  = Order::pluck('user_id')->toArray();
        $customerBookings = Booking::pluck('user_id')->toArray();

        $uniqueCustomers = collect($customerOrders)
                            ->merge($customerBookings)
                            ->unique()
                            ->count();

        $totalCustomers = $uniqueCustomers;

        $recentActivities = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalCustomers',
            'totalServices',
            'recentActivities'
        ));
    }
}