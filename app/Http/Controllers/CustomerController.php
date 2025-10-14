<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Product; // <- tambahkan ini
use App\Notifications\BookingCreated;

class CustomerController extends Controller
{
    public function services()
    {
        return view('customer.services');
    }

    public function booking()
    {
        return view('customer.booking');
    }

    public function storeBooking(Request $request)
    {
        $request->validate([
            'vehicle' => 'required|string|max:255',
            'booking_date' => 'required|date|after:yesterday',
            'notes' => 'nullable|string',
        ]);

        $booking = Booking::create([
            'user_id'         => auth()->id(),
            'vehicle_type'    => $request->vehicle,
            'tanggal_booking' => $request->booking_date,
            'catatan'         => $request->notes,
            'status'          => 'pending',
        ]);

        auth()->user()->notify(new BookingCreated($booking));

        return redirect()->route('customer.booking')
            ->with('success', 'Booking berhasil dibuat! Tunggu konfirmasi admin.');
    }

    public function products()
    {
        // ambil data produk dari DB
        $products = Product::latest()->paginate(9);

        // kirim ke view
        return view('customer.products', compact('products'));
    }

    public function productDetail(Product $product)
    {
        return view('customer.product-detail', compact('product'));
    }
}