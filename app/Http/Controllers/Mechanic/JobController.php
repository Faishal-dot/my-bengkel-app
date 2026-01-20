<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JobController extends Controller
{
    public function index()
    {
        // 1. Cari data mekanik yang terhubung dengan USER yang sedang login
        $mechanic = Mechanic::where('user_id', Auth::id())->first();

        if (!$mechanic) {
            return view('mechanic.jobs.index', ['bookings' => collect([])])
                   ->with('error', 'Profil mekanik tidak ditemukan.');
        }

        // 2. Ambil booking berdasarkan ID MEKANIK
        // PERBAIKAN: Tambahkan status 'selesai' agar tidak hilang setelah diupdate
        $bookings = Booking::with(['service', 'vehicle', 'user'])
            ->where('mechanic_id', $mechanic->id)
            ->whereIn('status', ['disetujui', 'proses', 'selesai']) 
            // Tambahkan filter agar status 'selesai' yang muncul hanya untuk hari ini saja (opsional)
            ->where(function($query) {
                $query->where('status', '!=', 'selesai')
                      ->orWhereDate('updated_at', Carbon::today());
            })
            // Urutkan: 'proses' paling atas, lalu 'disetujui', lalu 'selesai' paling bawah
            ->orderByRaw("FIELD(status, 'proses', 'disetujui', 'selesai') ASC")
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
                [
                    'status' => 'pending',
                    'amount' => $booking->service->price, // Pastikan nominal terisi otomatis dari harga servis
                    'user_id' => $booking->user_id
                ]
            );
        }

        return back()->with('success', 'Status pekerjaan berhasil diperbarui.');
    }
}