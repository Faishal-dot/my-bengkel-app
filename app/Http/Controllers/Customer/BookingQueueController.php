<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingQueueController extends Controller
{
    /**
     * Menampilkan antrian booking berdasarkan tanggal
     */
    public function index(Request $request)
    {
        // Ambil tanggal dari query (?date=xxxx), default ke hari ini jika kosong
        $date = $request->date ?? now()->toDateString();

        // Jika format tanggal tidak valid → pakai hari ini
        if (!strtotime($date)) {
            $date = now()->toDateString();
        }

        /** * PERBAIKAN: 
         * Bagian pengecekan "Jika tanggal sudah lewat paksa kembali ke hari ini" 
         * telah dihapus agar user bisa melihat histori antrian tanggal sebelumnya.
         */

        // Ambil semua booking pada tanggal tersebut
        // Ditambahkan relasi 'vehicle' jika di view Anda membutuhkannya
        $queues = Booking::with(['user', 'service', 'mechanic', 'vehicle'])
            ->whereDate('booking_date', $date)
            ->whereNotNull('queue_number') // Opsional: Hanya munculkan yang sudah punya nomor antrian
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('customer.booking-queue.index', [
            'queues'       => $queues,
            'selectedDate' => $date,
        ]);
    }
}