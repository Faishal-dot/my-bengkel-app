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
     * List booking milik customer (Status Aktif/Berjalan)
     */
    public function index()
    {
        $bookings = Booking::with(['service', 'vehicle', 'mechanic'])
            ->where('user_id', Auth::id())
            ->latest() 
            ->paginate(10);

        return view('customer.my-booking', compact('bookings'));
    }

    /**
     * Form tambah booking + auto select service
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
     * Simpan booking baru dengan Logika Diskon Terkunci
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
            'booking_date'     => 'required|date|after_or_equal:today',
            'notes'            => 'nullable|string',
            'complaint'        => 'nullable|string', 
            'mechanic_id'      => 'nullable|exists:mechanics,id',
        ]);

        // --- LOGIKA DISKON DIMULAI ---
        // Cari data service di database agar harga tidak bisa dimanipulasi dari inspect element browser
        $service = Service::findOrFail($request->service_id);

        // Cek: Jika discount_price diisi dan lebih besar dari 0, pakai harga diskon. 
        // Jika tidak ada diskon, pakai harga normal (price).
        $finalPrice = ($service->discount_price && $service->discount_price > 0) 
                      ? $service->discount_price 
                      : $service->price;
        // --- LOGIKA DISKON SELESAI ---

        // 2. SET VALUE TAMBAHAN
        $validated['user_id']        = Auth::id();
        $validated['status']         = 'menunggu'; 
        $validated['payment_status'] = 'unpaid';
        $validated['queue_number']   = null; 
        
        // Simpan harga final yang sudah dihitung ke kolom total_price
        // Ini memastikan Admin akan melihat nominal yang benar-benar dibayar customer
        $validated['total_price']    = $finalPrice; 

        // 3. SIMPAN KE DATABASE
        Booking::create($validated);

        // 4. REDIRECT DENGAN PESAN SUKSES
        return redirect()
            ->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibuat. Total Biaya: Rp ' . number_format($finalPrice, 0, ',', '.'));
    }

    /**
     * History booking
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