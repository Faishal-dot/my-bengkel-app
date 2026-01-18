<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        // 1. Ambil booking yang statusnya 'disetujui' atau 'proses'
        // 2. Filter agar hanya mekanik yang ditugaskan yang bisa melihat
        $bookings = Booking::with(['service', 'vehicle', 'user'])
            ->whereIn('status', ['disetujui', 'proses']) 
            ->where('mechanic_id', Auth::id()) 
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('mechanic.jobs', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        // Sesuaikan dengan enum/status di database Anda
        $request->validate([
            'status' => 'required|in:proses,selesai,cancel'
        ]);

        $booking->status = $request->status;
        $booking->save();

        if ($request->status == 'selesai') {
            Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->total_price ?? ($booking->service->price ?? 0),
                    'status' => 'unpaid',
                    'bank_name' => '-',
                    'account_number' => '-',
                    'account_holder' => '-',
                ]
            );
        }

        return back()->with('success', 'Status pekerjaan berhasil diperbarui.');
    }
}