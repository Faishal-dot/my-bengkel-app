<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // MENAMPILKAN HALAMAN "PEKERJAAN SAYA"
    public function index()
    {
        $mechanic = Mechanic::where('user_id', auth()->id())->first();

        if (!$mechanic) {
            return view('mechanic.jobs.index', ['bookings' => collect()]);
        }

        // Ambil Data Booking (Disetujui, Proses, Selesai)
        $bookings = Booking::with(['user', 'service', 'vehicle'])
            ->where('mechanic_id', $mechanic->id)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['disetujui', 'proses', 'selesai'])
            ->orderByRaw("FIELD(status, 'proses', 'disetujui', 'selesai')")
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('mechanic.jobs.index', compact('bookings'));
    }

    // UPDATE STATUS (KERJAKAN / SELESAI)
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $mechanic = Mechanic::where('user_id', auth()->id())->first();
        if (!$mechanic || $booking->mechanic_id !== $mechanic->id) {
            abort(403, 'Akses ditolak.');
        }

        if ($request->status == 'proses') {
            $booking->update(['status' => 'proses']);
            return back()->with('success', 'Status: Sedang Dikerjakan.');
        }

        if ($request->status == 'selesai') {
            $booking->update(['status' => 'selesai']);
            return back()->with('success', 'Pekerjaan Selesai!');
        }

        return back();
    }
}