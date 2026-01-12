<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ChatMessage; // Pastikan ini sesuai dengan model yang Anda gunakan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ==========================================
    // HALAMAN CHAT (Fix Error 403)
    // ==========================================
    public function show($bookingId)
    {
        // Load data booking beserta relasinya
        $booking = Booking::with(['messages.user', 'vehicle', 'service', 'user', 'mechanic'])->findOrFail($bookingId);
        
        $currentUser = Auth::user();

        // -----------------------------------------------------------
        // 1. PERBAIKAN VALIDASI AKSES (AUTHORIZATION)
        // -----------------------------------------------------------
        
        // Cek apakah dia pemilik booking (Customer)
        $isOwner = $currentUser->id == $booking->user_id;
        
        // Cek apakah dia Mekanik (Bolehkan semua mekanik akses)
        $isMechanic = $currentUser->role == 'mechanic';
        
        // Cek apakah dia Admin
        $isAdmin = $currentUser->role == 'admin';

        // Jika BUKAN Owner, BUKAN Mekanik, dan BUKAN Admin -> TENDANG (403)
        if (!$isOwner && !$isMechanic && !$isAdmin) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses chat ini.');
        }

        // -----------------------------------------------------------
        // 2. LOGIKA NAMA LAWAN BICARA (Untuk Header Chat)
        // -----------------------------------------------------------
        // Jika yang login Customer, lawan bicaranya "Mekanik" atau "Admin"
        // Jika yang login Mekanik/Admin, lawan bicaranya "Nama Customer"
        
        if ($currentUser->role == 'customer') {
            $chatPartnerName = $booking->mechanic ? $booking->mechanic->name : 'Admin / Bengkel';
        } else {
            $chatPartnerName = $booking->user ? $booking->user->name : 'Customer (Guest)';
        }

        return view('chat.show', compact('booking', 'chatPartnerName'));
    }

    // ==========================================
    // KIRIM PESAN
    // ==========================================
    public function send(Request $request, $bookingId)
    {
        $request->validate(['message' => 'required|string']);
        
        $booking = Booking::findOrFail($bookingId);

        // Cek apakah chat aktif
        if (!$booking->is_chat_active) {
            return back()->with('error', 'Sesi chat ini sudah ditutup oleh mekanik.');
        }

        ChatMessage::create([
            'booking_id' => $bookingId,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return back(); // Kembali ke halaman chat
    }

    // ==========================================
    // TOGGLE STATUS (ON/OFF CHAT)
    // ==========================================
    public function toggleStatus($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        // Validasi: Hanya Admin atau Mekanik yang boleh menutup chat
        if (Auth::user()->role == 'customer') {
            abort(403, 'Customer tidak dapat mengubah status sesi chat.');
        }

        // Toggle status (True jadi False, False jadi True)
        $booking->is_chat_active = !$booking->is_chat_active;
        $booking->save();

        $status = $booking->is_chat_active ? 'dibuka' : 'ditutup';
        return back()->with('success', "Sesi chat berhasil $status.");
    }

    // ==========================================
    // REALTIME FETCH (Auto-Reload Pesan)
    // ==========================================
    public function fetchMessages($bookingId)
    {
        // SAYA UBAH: Menggunakan 'ChatMessage' dan 'booking_id' agar sesuai dengan kode di atas
        $chats = ChatMessage::with('user') // Eager load user agar tidak N+1 query
                    ->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'asc')
                    ->get();

        // Pastikan Anda sudah membuat file view: resources/views/partials/chat-messages.blade.php
        return view('partials.chat-messages', compact('chats'))->render();
    }
}