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
        // Mengurutkan berdasarkan booking_date ASC agar jam terawal di atas
        $bookings = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->orderBy('booking_date', 'asc') 
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

        $request->validate([
            'status' => 'required|in:pending,disetujui,proses,selesai,ditolak',
            'mechanic_id' => 'nullable|exists:mechanics,id',
        ]);

        $booking->mechanic_id = $request->mechanic_id;
        $booking->status = $request->status;

        if ($request->status === 'disetujui') {
            
            // 1. Logika Antrian Otomatis
            if ($booking->mechanic_id && $booking->queue_number === null) {
                // Mengambil tanggal saja tanpa jam untuk filter antrian hari itu
                $dateOnly = Carbon::parse($booking->booking_date)->toDateString();
                
                $lastQueue = Booking::where('mechanic_id', $booking->mechanic_id)
                    ->whereDate('booking_date', $dateOnly)
                    ->whereNotIn('status', ['ditolak', 'batal'])
                    ->max('queue_number');

                $booking->queue_number = $lastQueue ? ($lastQueue + 1) : 1;
            }

            // 2. Buat Record Pembayaran Otomatis
            // Gunakan harga diskon jika ada, jika tidak pakai harga normal
            $finalPrice = 0;
            if ($booking->service) {
                $finalPrice = $booking->service->discount_price ?? $booking->service->price;
            }

            Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $finalPrice,
                    'method' => 'cash',
                    'status' => 'pending', 
                    'bank_name' => '-',
                    'account_number' => '-',
                    'account_holder' => '-',
                ]
            );
        }

        $booking->save();

        return redirect()->back()->with('success', 'Berhasil memperbarui data!');
    }

    public function assignMechanic(Request $request, $id)
    {
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