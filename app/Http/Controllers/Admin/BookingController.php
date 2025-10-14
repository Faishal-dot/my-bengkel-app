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
        'mechanic_id' => 'nullable|exists:users,id',
    ]);

    $booking->mechanic_id = $request->mechanic_id;
    $booking->save();

    return redirect()->route('admin.bookings.show', $id)
        ->with('success', 'Mekanik berhasil diperbarui!');
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