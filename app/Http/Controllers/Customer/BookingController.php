<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Daftar booking milik customer
     */
    public function index()
    {
        $bookings = Booking::with(['service', 'vehicle', 'mechanic']) // ✅ FIX: load mekanik
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
        $services  = Service::all();
        $vehicles  = Auth::user()->vehicles ?? collect();
        $mechanics = Mechanic::all(); // tetap

        return view('customer.booking', compact('services', 'vehicles', 'mechanics'));
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'vehicle_id'   => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'notes'        => 'nullable|string',
            'mechanic_id'  => 'nullable|exists:mechanics,id',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        Booking::create($validated);

        return redirect()
            ->route('customer.booking.index')
            ->with('success', 'Booking berhasil dikirim, tunggu konfirmasi admin.');
    }

    /**
     * History booking
     */
    public function history()
    {
        $bookings = Booking::with(['service', 'vehicle', 'mechanic']) // ✅ FIX: load mekanik
            ->where('user_id', Auth::id())
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('customer.booking-history', compact('bookings'));
    }
}