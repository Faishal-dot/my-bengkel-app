<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * List booking untuk mekanik yang sedang login
     */
    public function index()
    {
        $bookings = Booking::with(['service', 'vehicle', 'user'])
            ->where('mechanic_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mechanic.bookings.index', compact('bookings'));
    }

    /**
     * Mekanik update status booking
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:proses,selesai,cancel'
        ]);

        // Update status tanpa membuat payment
        $booking->status = $request->status;
        $booking->save();

        // ❌ Tidak membuat pembayaran
        // ❌ Tidak masuk ke pembayaran admin
        // ✔️ Customer nanti yang membuat pembayaran manual

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }
}