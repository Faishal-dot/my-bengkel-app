<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
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
     * Status helper
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}