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
            // Menampilkan yang terbaru dulu
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
        // Pastikan user punya kendaraan, jika tidak return collection kosong
        $vehicles  = Auth::user()->vehicles ?? collect(); 
        $mechanics = Mechanic::all();
        $products  = Product::all();

        // Ambil service_id dari URL jika ada (fitur auto-select dari halaman depan)
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
     * Simpan booking baru
     */
    public function store(Request $request)
    {
        // 1. VALIDASI DATA
        $validated = $request->validate([
            'service_id'   => 'required|exists:services,id',
            'vehicle_id'   => 'required|exists:vehicles,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'notes'        => 'nullable|string',
            'complaint'    => 'nullable|string', // Keluhan kerusakan
            'mechanic_id'  => 'nullable|exists:mechanics,id',
        ]);

        // 2. SET DEFAULT VALUE
        $validated['user_id']        = Auth::id();
        
        // Status awal 'menunggu' agar sinkron dengan Admin Controller
        $validated['status']         = 'menunggu'; 
        $validated['payment_status'] = 'unpaid';

        // 3. LOGIKA ANTRIAN (DIBUAT NULL)
        // Kita set NULL secara eksplisit.
        // Nomor antrian akan di-generate otomatis oleh Admin saat status diubah jadi 'Disetujui'.
        $validated['queue_number'] = null; 

        // 4. SIMPAN KE DATABASE
        Booking::create($validated);

        return redirect()
            ->route('customer.booking.index')
            ->with('success', 'Booking berhasil dibuat. Mohon tunggu persetujuan Admin untuk mendapatkan nomor antrian.');
    }

    /**
     * History booking (Riwayat selesai/batal)
     * Opsional: Bisa dipisah view-nya atau digabung dengan index
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