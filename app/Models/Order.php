<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini untuk relasi detail
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id', // Tetap ada sebagai referensi utama/produk pertama
        'service_id',
        'quantity',
        'total_price',
        'status',
        'payment_status', // Pastikan ini ada karena digunakan di Controller
    ];

    /**
     * Relasi ke Payment
     */
    public function payment(): HasOne
    {
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
     * Relasi ke Order Details (PENTING untuk menampilkan banyak produk)
     * Kita gunakan nama 'orderDetails' agar sesuai dengan pengecekan di View
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
}