<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil tanggal dari filter, kalau kosong set ke HARI INI
        $date = $request->input('date') ?: Carbon::today()->toDateString();

        // 2. Query Hardcore: Kita pakai whereRaw untuk memaksa DB membuang data JAM-nya
        $queues = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->whereIn('status', ['disetujui', 'proses', 'selesai'])
            ->whereRaw("DATE(booking_date) = ?", [$date]) // Memaksa DB cek tanggal saja
            ->orderBy('queue_number', 'asc')
            ->get();

        // 3. Debugging: Kalau data masih kosong, kita paksa ambil TANPA filter tanggal 
        // supaya kamu tahu datanya benar-benar ada atau tidak di tabel.
        if ($queues->isEmpty()) {
            $allApproved = Booking::whereIn('status', ['disetujui', 'proses', 'selesai'])->count();
            if($allApproved > 0) {
                session()->now('warning', "Ada $allApproved data disetujui, tapi tidak cocok dengan tanggal $date");
            }
        }

        return view('admin.queue.index', compact('queues', 'date'));
    }
}