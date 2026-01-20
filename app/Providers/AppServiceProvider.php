<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Booking;
use App\Models\Payment;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailwind');

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $user = auth()->user();

                // ============================================================
                // LOGIKA UNTUK ADMIN
                // ============================================================
                if ($user->role === 'admin') {
                    $isOpeningBooking = request()->routeIs('admin.bookings.*');
                    $isOpeningPayment = request()->routeIs('admin.payments.*');

                    // Admin melihat angka selama status masi 'pending'
                    $pendingBookingsCount = Booking::where('status', 'pending')->count();
                    $pendingPaymentsCount = Payment::where('status', 'pending')
                                                  ->whereNotNull('proof')
                                                  ->count();

                    $view->with([
                        'pendingBookingsCount' => $isOpeningBooking ? 0 : $pendingBookingsCount,
                        'pendingPaymentsCount' => $isOpeningPayment ? 0 : $pendingPaymentsCount,
                    ]);
                }

                // ============================================================
                // LOGIKA UNTUK CUSTOMER
                // ============================================================
                if ($user->role === 'customer') {
                    
                    // 1. Notifikasi Booking (Konfirmasi Admin)
                    $isOpeningCustBooking = request()->routeIs('customer.booking.*');
                    if ($isOpeningCustBooking) session(['last_viewed_customer_booking' => now()]);
                    $lastViewedCustBooking = session('last_viewed_customer_booking');

                    $approvedBookingQuery = Booking::where('user_id', $user->id)
                                                    ->where('status', 'disetujui');
                    
                    if ($lastViewedCustBooking && !$isOpeningCustBooking) {
                        $approvedBookingQuery->where('updated_at', '>', $lastViewedCustBooking);
                    }

                    // 2. Notifikasi Pembayaran (Wajib muncul jika status 'unpaid')
                    // Kita buat lebih sederhana agar pasti muncul di sidebar
                    $custUnpaidCount = Payment::whereHas('booking', function($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        })
                                        ->where('status', 'unpaid')
                                        ->count();

                    // Kirim variabel ke view
                    $view->with([
                        'custApprovedCount' => $isOpeningCustBooking ? 0 : $approvedBookingQuery->count(),
                        'custUnpaidCount' => $custUnpaidCount, // Dibuat selalu muncul untuk testing
                    ]);
                }
            }
        });
    }
}