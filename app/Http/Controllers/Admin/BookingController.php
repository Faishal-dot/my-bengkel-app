<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Mechanic;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon; // Tambahkan Carbon untuk olah tanggal

class BookingController extends Controller
{
    // ============================
    // LIST BOOKING
    // ============================
    public function index()
    {
        $bookings = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->latest()
            ->paginate(10); // Gunakan paginate agar tidak berat jika data banyak

        return view('admin.bookings.index', compact('bookings'));
    }

    // ============================
    // DETAIL BOOKING
    // ============================
    public function show($id)
    {
        $booking = Booking::with(['user', 'service', 'vehicle', 'mechanic'])
            ->findOrFail($id);

        $mechanics = Mechanic::all();

        return view('admin.bookings.show', compact('booking', 'mechanics'));
    }

    // ============================
    // UPDATE STATUS BOOKING (INTI LOGIC)
    // ============================
    public function update(Request $request, $id)
    {
        $booking = Booking::with('service')->findOrFail($id);

        // Validasi
        $request->validate([
            'status' => 'required|in:pending,disetujui,rejected,proses,selesai',
            'mechanic_id' => 'nullable|exists:mechanics,id',
        ]);

        // 1. UPDATE MEKANIK (Jika input ada)
        if ($request->filled('mechanic_id')) {
            // Jika mekanik diganti, reset nomor antrian
            if ($booking->mechanic_id != $request->mechanic_id) {
                $booking->queue_number = null; 
            }
            $booking->mechanic_id = $request->mechanic_id;
        }

        // 2. LOGIC SAAT STATUS JADI 'DISETUJUI'
        if ($request->status === 'disetujui') {
            
            // A. Generate Nomor Antrian (Jika belum ada & mekanik sudah dipilih)
            if ($booking->mechanic_id && $booking->queue_number === null) {
                
                // Ambil tanggal booking (format Y-m-d)
                $bookingDate = Carbon::parse($booking->booking_date)->format('Y-m-d');

                // Cari nomor antrian TERBESAR untuk:
                // - Mekanik ini
                // - Tanggal ini
                // - Status SELAIN ditolak/batal (jadi 'proses' & 'selesai' tetap dihitung)
                $lastQueue = Booking::where('mechanic_id', $booking->mechanic_id)
                    ->whereDate('booking_date', $bookingDate)
                    ->whereNotIn('status', ['rejected', 'ditolak', 'batal'])
                    ->max('queue_number');

                // Nomor baru = Max + 1. Jika belum ada, mulai dari 1.
                $booking->queue_number = $lastQueue ? ($lastQueue + 1) : 1;
            }

            // B. Generate Tagihan (Payment) Otomatis
            Payment::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $booking->service->price ?? 0,
                    'status' => 'unpaid',
                    'bank_name' => '-',
                    'account_number' => '-',
                    'account_holder' => '-',
                    'payment_date' => null
                ]
            );
        }

        // 3. SIMPAN PERUBAHAN
        $booking->status = $request->status;
        $booking->save();

        return redirect()
            ->back()
            ->with('success', 'Status booking diperbarui! Antrian: ' . ($booking->queue_number ?? '-'));
    }

    // ============================
    // ASSIGN MEKANIK (Opsional / Helper)
    // ============================
    public function assignMechanic(Request $request, $id)
    {
        // Fungsi ini bisa dipanggil via AJAX atau Form terpisah
        // Tapi logic utamanya sebaiknya digabung ke update() seperti di atas agar konsisten
        return $this->update($request->merge(['status' => 'disetujui']), $id);
    }

    // ============================
    // DELETE BOOKING
    // ============================
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}