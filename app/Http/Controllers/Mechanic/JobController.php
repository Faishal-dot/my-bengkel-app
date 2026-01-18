<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index()
    {
        // 1. Cari data mekanik yang terhubung dengan USER yang sedang login
        $mechanic = Mechanic::where('user_id', Auth::id())->first();

        if (!$mechanic) {
            return view('mechanic.jobs', ['bookings' => collect([])])
                   ->with('error', 'Profil mekanik tidak ditemukan.');
        }

        // 2. Ambil booking berdasarkan ID MEKANIK (bukan user id)
        $bookings = Booking::with(['service', 'vehicle', 'user'])
            ->whereIn('status', ['disetujui', 'proses']) 
            ->where('mechanic_id', $mechanic->id) // Gunakan ID dari tabel mechanics
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('mechanic.jobs.index', compact('bookings'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:proses,selesai'
        ]);

        $booking->status = $request->status;
        $booking->save();

        // Jika selesai, pastikan payment status tetap aman
        if ($request->status == 'selesai') {
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                ['status' => 'pending'] // Menunggu customer bayar
            );
        }

        return back()->with('success', 'Status pekerjaan berhasil diperbarui.');
    }
}