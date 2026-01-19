<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne; // Tambahkan ini
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'service_id',
        'quantity',
        'total_price',
        'status',
    ];

    /**
     * ✅ Relasi ke Payment
     * Inilah yang menyebabkan error "Call to undefined relationship [payment]"
     */
    public function payment(): HasOne
    {
        // Parameter kedua adalah foreign key di tabel payments
        return $this->hasOne(Payment::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Jika Anda menggunakan sistem detail order (opsional)
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
}