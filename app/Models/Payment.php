<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'order_id',      // <--- TAMBAHKAN INI
        'bank_name',
        'account_number',
        'account_holder',
        'amount',
        'proof',
        'status',
    ];

    /**
     * Casting untuk kolom tertentu
     */
    protected $casts = [
        'amount' => 'integer',
        'status' => 'string',
    ];

    /**
     * Default value status jika tidak dikirim
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Relasi ke Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relasi ke Order (Pembelian Produk)
     * Tambahkan ini agar model mengenali tabel Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class); // Pastikan Anda punya model Order
    }

    /**
     * Status helper
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    // Saran: Sesuaikan dengan status di Controller Admin Anda (tadi di Admin pakai 'paid')
    public function isPaid()
    {
        return $this->status === 'paid' || $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected' || $this->status === 'failed';
    }
}