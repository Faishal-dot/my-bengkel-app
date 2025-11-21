<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use App\Notifications\BookingStatusNotification;

class BookingController extends Controller
{
    // ✅ Tampilkan semua booking
    public function index()
    {
        $bookings = Booking::with(['user', 'service', 'vehicle', 'mechanic'])->latest()->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    // ✅ Tampilkan detail booking
    public function show($id)
    {
        $booking = Booking::with(['user', 'service', 'vehicle', 'mechanic'])->findOrFail($id);
        $mechanics = Mechanic::all();
        return view('admin.bookings.show', compact('booking', 'mechanics'));
    }

    // ✅ Update status atau mekanik
    public function update(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    $request->validate([
        'status' => 'required|in:pending,approved,rejected',
        'mechanic_id' => 'nullable|exists:mechanics,id',
    ]);

    // Jika mekanik diisi, update
    if ($request->filled('mechanic_id')) {
        $booking->mechanic_id = $request->mechanic_id;
    }

    // Jika status berubah menjadi approved → kasih nomor antrian
    if ($request->status === 'approved' && $booking->queue_number === null) {

        // Ambil nomor antrian terakhir di hari yang sama
        $lastQueue = Booking::whereDate('booking_date', $booking->booking_date)
            ->where('status', 'approved')
            ->max('queue_number');

        // Tentukan nomor antrian baru
        $nextQueue = $lastQueue ? $lastQueue + 1 : 1;

        $booking->queue_number = $nextQueue;
    }

    // Update status
    $booking->status = $request->status;

    // Simpan
    $booking->save();

    return redirect()
        ->route('admin.bookings.index')
        ->with('success', 'Status booking berhasil diperbarui!');
}

    // ✅ Hapus booking
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}