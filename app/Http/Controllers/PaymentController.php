<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Order; 
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
        // Mencari payment yang booking-nya milik user login ATAU order-nya milik user login
        $payments = Payment::with(['booking.service', 'booking.vehicle', 'order.product'])
            ->where(function($query) {
                $query->whereHas('booking', function ($q) {
                    $q->where('user_id', auth()->id());
                })->orWhereHas('order', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Update status otomatis jika belum bayar
        foreach ($payments as $payment) {
            if ($payment->booking && $payment->booking->status === 'selesai') {
                if (empty($payment->proof) && $payment->status !== 'unpaid') {
                    $payment->update(['status' => 'unpaid']);
                    $payment->status = 'unpaid'; 
                }
            }
        }

        return view('customer.payment.index', compact('payments'));
    }

    // ==========================================
    // 2. HALAMAN BAYAR BOOKING (CREATE)
    // ==========================================
    public function create($booking_id)
    {
        $booking = Booking::with(['service', 'vehicle'])->findOrFail($booking_id);

        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        if ($booking->status !== 'selesai') {
            return redirect()->route('customer.payment.index')
                ->with('error', 'Tagihan belum tersedia. Tunggu mekanik menyelesaikan pekerjaan.');
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

        if (in_array($payment->status, ['pending', 'paid', 'approved', 'success']) && !empty($payment->proof)) {
             return redirect()->route('customer.payment.index')
                ->with('info', 'Pembayaran sedang diverifikasi atau sudah lunas.');
        }

        return view('customer.payment.create', compact('booking', 'payment'));
    }

    // ==========================================
    // 2b. HALAMAN BAYAR PRODUK (CREATE)
    // ==========================================
    public function createProduct($order_id)
    {
        $order = Order::with('product')->findOrFail($order_id);

        if ($order->user_id !== auth()->id()) {
            abort(403, 'Akses Ditolak.');
        }

        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            [
                'amount' => $order->total_price,
                'status' => 'unpaid',
                'bank_name' => '-',      
                'account_number' => '-', 
                'account_holder' => '-', 
            ]
        );

        if ($payment->status !== 'unpaid' && !empty($payment->proof)) {
             return redirect()->route('customer.payment.index')
                ->with('info', 'Pembayaran sudah dikirim.');
        }

        return view('customer.payment.create_product', compact('order', 'payment'));
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

        $payment = Payment::with(['booking', 'order'])->findOrFail($request->payment_id);

        // Validasi kepemilikan
        $owner_id = $payment->booking ? $payment->booking->user_id : ($payment->order ? $payment->order->user_id : null);
        if ($owner_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->hasFile('proof')) {
            if ($payment->proof && Storage::disk('public')->exists($payment->proof)) {
                Storage::disk('public')->delete($payment->proof);
            }
            $path = $request->file('proof')->store('payment_proofs', 'public');
            $payment->proof = $path;
        }

        $payment->update([
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status'         => 'pending', 
        ]);

        if ($payment->booking_id) {
            $payment->booking->update(['payment_status' => 'pending']);
        } 
        
        if ($payment->order_id) {
            $payment->order->update(['status' => 'pending']);
        }

        return redirect()->route('customer.payment.index')
            ->with('success', 'Pembayaran dikirim! Menunggu konfirmasi Admin.');
    }

    // ==========================================
    // 4. BATALKAN TRANSAKSI (DESTROY)
    // ==========================================
    public function destroy($id)
    {
        try {
            $payment = Payment::with(['booking', 'order'])->findOrFail($id);

            // Cek kepemilikan melalui relasi booking atau order
            $owner_id = $payment->booking ? $payment->booking->user_id : ($payment->order ? $payment->order->user_id : null);

            if ($owner_id !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan transaksi ini.');
            }

            // Hapus file bukti jika ada
            if ($payment->proof) {
                Storage::disk('public')->delete($payment->proof);
            }

            // Kembalikan status booking jika diperlukan
            if ($payment->booking) {
                $payment->booking->update(['payment_status' => 'unpaid']);
            }
            
            // Kembalikan status order jika diperlukan
            if ($payment->order) {
                $payment->order->update(['status' => 'menunggu']);
            }

            $payment->delete();

            return redirect()->route('customer.payment.index')
                             ->with('success', 'Transaksi berhasil dibatalkan.');
                             
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}