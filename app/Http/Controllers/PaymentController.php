<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    // ==========================================
    // 1. HALAMAN LIST (INDEX)
    // ==========================================
    public function index()
    {
        // Ambil data payment
        $payments = Payment::with(['booking.service', 'booking.vehicle'])
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // [LOGIC AUTO FIX - PRE PAYMENT]
        // Jika Booking sudah 'disetujui' tapi Payment statusnya masih aneh/pending tanpa bukti,
        // Kita reset jadi 'unpaid' agar tombol bayar muncul.
        foreach ($payments as $payment) {
            // Cek jika booking sudah disetujui admin
            if ($payment->booking->status === 'disetujui') {
                // Jika belum ada bukti transfer, status wajib 'unpaid'
                if (empty($payment->proof) && $payment->status !== 'unpaid') {
                    $payment->update(['status' => 'unpaid']);
                    $payment->status = 'unpaid'; // Update variabel agar view langsung berubah
                }
            }
        }

        return view('customer.payment.index', compact('payments'));
    }

    // ==========================================
    // 2. HALAMAN BAYAR (CREATE)
    // ==========================================
    public function create($booking_id)
    {
        $booking = Booking::with(['service', 'vehicle'])->findOrFail($booking_id);

        // 1. Security Check
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        // 2. [LOGIC BARU] Validasi Status Booking
        // Pembayaran HANYA BOLEH dilakukan saat status "Disetujui".
        // Jika masih "Pending" (belum dicek admin), tolak akses.
        if ($booking->status === 'pending') {
            return redirect()->route('customer.payment.index')
                ->with('error', 'Mohon tunggu Admin menyetujui booking Anda terlebih dahulu.');
        }
        
        // 3. Buat/Ambil Data Payment
        $payment = Payment::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount' => $booking->service->price ?? 0,
                'status' => 'unpaid',
                'bank_name' => '-',      
                'account_number' => '-', 
                'account_holder' => '-', 
            ]
        );

        // 4. [LOGIC PAKSA UNPAID]
        // Jika booking 'disetujui', tapi customer belum upload bukti (proof kosong),
        // Maka status HARUS 'unpaid'. 
        $bookingApproved = $booking->status === 'disetujui';
        // Cek apakah belum bayar (nama bank masih default '-' atau bukti kosong)
        $noProofYet = empty($payment->proof) || $payment->bank_name === '-' || $payment->bank_name === null;

        if ($bookingApproved && $noProofYet) {
            if ($payment->status !== 'unpaid') {
                $payment->update(['status' => 'unpaid']);
                $payment->refresh();
            }
        }

        // 5. Redirect jika sudah lunas/sedang diverifikasi
        // Hanya redirect jika statusnya pending/paid DAN bukti transfer sudah ada
        if (in_array($payment->status, ['pending', 'paid', 'approved', 'success']) && !empty($payment->proof)) {
             return redirect()->route('customer.payment.index')
                ->with('info', 'Pembayaran sedang diverifikasi atau sudah lunas.');
        }

        return view('customer.payment.create', compact('booking', 'payment'));
    }

    // ==========================================
    // 3. PROSES SIMPAN (STORE)
    // ==========================================
    public function store(Request $request)
    {
        $request->validate([
            'payment_id'     => 'required|exists:payments,id',
            'bank_name'      => 'required|string|max:50',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
            'proof'          => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Upload Gambar
        if ($request->hasFile('proof')) {
            if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                Storage::disk('public')->delete($payment->proof);
            }
            $path = $request->file('proof')->store('payment_proofs', 'public');
            $payment->proof = $path;
        }

        // Update Data Payment -> Pending
        $payment->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'pending', 
        ]);

        // Update Booking -> Payment Status Pending
        // Status utama booking TETAP 'disetujui' (jangan ubah jadi selesai dulu, biar mekanik yang ubah nanti)
        $payment->booking->update([
            'payment_status' => 'pending'
        ]);

        return redirect()->route('customer.payment.index')
            ->with('success', 'Pembayaran dikirim! Menunggu konfirmasi Admin sebelum diproses.');
    }
}