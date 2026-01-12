<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * MENAMPILKAN HALAMAN "PEKERJAAN SAYA"
     */
    public function index()
    {
        // 1. Ambil data mekanik yang sedang login
        $mechanic = Mechanic::where('user_id', auth()->id())->first();

        // 2. Jika user login tapi datanya tidak ada di tabel mechanics, kembalikan list kosong
        if (!$mechanic) {
            return view('mechanic.jobs.index', ['bookings' => collect()]);
        }

        // 3. Ambil Data Booking yang relevan
        // Syarat: Milik mekanik ini, Sudah Bayar, Statusnya (Disetujui/Proses/Selesai)
        $bookings = Booking::with(['user', 'service', 'vehicle'])
            ->where('mechanic_id', $mechanic->id)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['disetujui', 'proses', 'selesai'])
            // Sorting: Proses (sedang dikerjakan) ditaruh paling atas, baru yang baru masuk
            ->orderByRaw("FIELD(status, 'proses', 'disetujui', 'selesai')")
            ->orderBy('booking_date', 'asc')
            ->get();

        // 4. Kirim ke View
        return view('mechanic.jobs.index', compact('bookings'));
    }

    /**
     * UPDATE STATUS (KERJAKAN / SELESAI)
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        // 1. Validasi Keamanan: Pastikan booking ini milik mekanik yang login
        $mechanic = Mechanic::where('user_id', auth()->id())->first();
        if (!$mechanic || $booking->mechanic_id !== $mechanic->id) {
            abort(403, 'Akses ditolak. Ini bukan pekerjaan Anda.');
        }

        // 2. Logika Update Status
        if ($request->status == 'proses') {
            $booking->update(['status' => 'proses']);
            return back()->with('success', 'Status diperbarui: Sedang Dikerjakan 🚀');
        }

        if ($request->status == 'selesai') {
            $booking->update(['status' => 'selesai']);
            return back()->with('success', 'Pekerjaan Selesai! Kerja bagus 👍');
        }

        return back();
    }
}