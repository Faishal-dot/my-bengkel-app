<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $mechanic = Mechanic::where('user_id', auth()->id())->first();

        // Default value jika belum jadi mekanik
        $total_jobs = 0;
        $jobs_processing = 0;
        $jobs_done_today = 0;

        if ($mechanic) {
            $baseQuery = Booking::where('mechanic_id', $mechanic->id)->where('payment_status', 'paid');

            $total_jobs = (clone $baseQuery)->whereIn('status', ['disetujui', 'proses', 'selesai'])->count();
            $jobs_processing = (clone $baseQuery)->where('status', 'proses')->count();
            $jobs_done_today = (clone $baseQuery)->where('status', 'selesai')->whereDate('updated_at', Carbon::today())->count();
        }

        // Kita TIDAK kirim $bookings (tabel) ke dashboard lagi
        return view('mechanic.dashboard', compact('total_jobs', 'jobs_processing', 'jobs_done_today'));
    }
}