<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Mechanic;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * List booking milik customer (Urutkan dari jadwal terdekat)
     */
    public function index()
    {
        $bookings = Booking::with(['service', 'vehicle', 'mechanic'])
            ->where('user_id', Auth::id())
            // Diubah dari latest() ke orderBy agar customer lihat jadwal terdekat mereka di paling atas
            ->orderBy('booking_date', 'asc') 
            ->paginate(10);

        return view('customer.my-booking', compact('bookings'));
    }

    /**
     * Form tambah booking
     */
    public function create(Request $request)
    {
        $services  = Service::all();
        $vehicles  = Auth::user()->vehicles ?? collect(); 
        $mechanics = Mechanic::all();
        $products  = Product::all();

        $selectedServiceId = $request->service_id; 

        return view('customer.booking', compact(
            'services', 
            'vehicles', 
            'mechanics', 
            'products',
            'selectedServiceId'
        ));
    }

    /**
     * Simpan booking baru (Mendukung Jam/Waktu)
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DATA
        $validated = $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_address' => 'required|string',
            'service_id'       => 'required|exists:services,id',
            'vehicle_id'       => 'required|exists:vehicles,id',
            // booking_date sekarang harus menyertakan jam agar tidak 00:00
            'booking_date'     => 'required|date|after_or_equal:now',
            'notes'            => 'nullable|string',
            'complaint'        => 'nullable|string', 
            'mechanic_id'      => 'nullable|exists:mechanics,id',
        ]);

        $service = Service::findOrFail($request->service_id);

        $finalPrice = ($service->discount_price && $service->discount_price > 0) 
                      ? $service->discount_price 
                      : $service->price;

        // 2. SET VALUE TAMBAHAN
        $validated['user_id']        = Auth::id();
        $validated['status']         = 'pending';
        $validated['payment_status'] = 'unpaid';
        $validated['queue_number']   = null; 
        $validated['total_price']    = $finalPrice; 

        // 3. SIMPAN KE DATABASE
        Booking::create($validated);

        return redirect()
            ->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibuat untuk tanggal ' . date('d-m-Y H:i', strtotime($request->booking_date)));
    }

    /**
     * History booking (Tetap tampilkan yang terbaru selesai di atas)
     */
    public function history()
    {
        $bookings = Booking::with(['service', 'vehicle', 'mechanic'])
            ->where('user_id', Auth::id())
            ->orderByDesc('booking_date')
            ->paginate(10);

        return view('customer.booking-history', compact('bookings'));
    }
}