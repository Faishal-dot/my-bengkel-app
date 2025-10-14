<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Mechanic; // ✅ Tambahan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Daftar booking milik customer
     */
    public function index()
    {
        $bookings = Booking::with(['service', 'vehicle'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.my-booking', compact('bookings'));
    }

    /**
     * Form tambah booking
     */
    public function create()
    {
        $services = Service::all();
        $vehicles = Auth::user()->vehicles ?? collect(); // antisipasi jika user belum punya kendaraan
        $mechanics = Mechanic::all(); // ✅ Tambahan

        return view('customer.booking', compact('services', 'vehicles', 'mechanics')); // ✅ kirim juga ke view
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request)
    {
        // ✅ Validasi input user
        $validated = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'vehicle_id'   => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'notes'        => 'nullable|string',
            'mechanic_id'  => 'nullable|exists:mechanics,id',
        ]);

        // ✅ Simpan booking ke database (langsung lewat relasi user)
        $booking = Auth::user()->bookings()->create($validated);

        return redirect()
            ->route('customer.booking.index')
            ->with('success', 'Booking berhasil dikirim, tunggu konfirmasi admin.');
    }

    /**
     * History booking
     */
    public function history()
    {
        $bookings = Booking::with(['service', 'vehicle'])
            ->where('user_id', Auth::id())
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('customer.booking-history', compact('bookings'));
    }
}