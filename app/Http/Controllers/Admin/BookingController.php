<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->latest()
            ->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->findOrFail($id);
        $mechanics = Mechanic::all();
        return view('admin.bookings.show', compact('booking', 'mechanics'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::with('service')->findOrFail($id);

        // Validasi disesuaikan dengan enum baru di database (Bahasa Indonesia)
        $request->validate([
            'status' => 'required|in:pending,disetujui,proses,selesai,ditolak',
            'mechanic_id' => 'nullable|exists:mechanics,id',
        ]);

        $booking->mechanic_id = $request->mechanic_id;
        $booking->status = $request->status;

        // Jika status yang dikirim adalah 'disetujui'
        if ($request->status === 'disetujui') {
            
            // 1. Logika Antrian Otomatis
            if ($booking->mechanic_id && $booking->queue_number === null) {
                $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');
                
                $lastQueue = Booking::where('mechanic_id', $booking->mechanic_id)
                    ->whereDate('booking_date', $bookingDate)
                    ->whereNotIn('status', ['ditolak', 'batal'])
                    ->max('queue_number');

                $booking->queue_number = $lastQueue ? ($lastQueue + 1) : 1;
            }

            // 2. Buat Record Pembayaran Otomatis
            Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->service->price ?? 0,
                    'method' => 'cash',
                    'status' => 'pending', 
                    'bank_name' => '-',
                    'account_number' => '-',
                    'account_holder' => '-',
                ]
            );
        }

        $booking->save();

        return redirect()->back()->with('success', 'Berhasil memperbarui data dan menugaskan mekanik!');
    }

    public function assignMechanic(Request $request, $id)
    {
        // Langsung paksa status ke 'disetujui' saat admin pilih mekanik
        $request->merge(['status' => 'disetujui']); 
        return $this->update($request, $id);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking dihapus.');
    }
}