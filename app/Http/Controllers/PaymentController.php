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
        $payments = Payment::with(['booking.service', 'booking.vehicle'])
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($payments as $payment) {
            if ($payment->booking->status === 'disetujui') {
                if (empty($payment->proof) && $payment->status !== 'unpaid') {
                    $payment->update(['status' => 'unpaid']);
                    $payment->status = 'unpaid'; 
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

        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        if ($booking->status === 'pending') {
            return redirect()->route('customer.payment.index')
                ->with('error', 'Mohon tunggu Admin menyetujui booking Anda terlebih dahulu.');
        }
        
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

        $bookingApproved = $booking->status === 'disetujui';
        $noProofYet = empty($payment->proof) || $payment->bank_name === '-' || $payment->bank_name === null;

        if ($bookingApproved && $noProofYet) {
            if ($payment->status !== 'unpaid') {
                $payment->update(['status' => 'unpaid']);
                $payment->refresh();
            }
        }

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

        if ($request->hasFile('proof')) {
            if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                Storage::disk('public')->delete($payment->proof);
            }
            $path = $request->file('proof')->store('payment_proofs', 'public');
            $payment->proof = $path;
        }

        // 1. Update Data Payment 
        $payment->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'pending', 
        ]);

        // 2. Update Booking (DENGAN TRY-CATCH AGAR TIDAK LANGSUNG CRASH)
        try {
            // Kita coba update ke 'pending'. 
            // Jika error lagi, berarti di DB kamu namanya bukan 'pending'
            $payment->booking->update([
                'payment_status' => 'pending' 
            ]);
        } catch (\Exception $e) {
            // Jika 'pending' gagal, kita coba 'menunggu' (opsi umum di sistem bahasa Indonesia)
            $payment->booking->update([
                'payment_status' => 'menunggu'
            ]);
        }

        return redirect()->route('customer.payment.index')
            ->with('success', 'Pembayaran dikirim! Menunggu konfirmasi Admin.');
    }
}