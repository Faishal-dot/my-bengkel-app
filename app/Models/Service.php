<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon; // Tambahkan ini di atas

class Service extends Model
{
    use HasFactory;

    // Tambahkan discount_start dan discount_end ke fillable
    protected $fillable = ['name', 'price', 'discount_price', 'discount_start', 'discount_end', 'description'];

    // Cast kolom menjadi objek Carbon agar mudah dimanipulasi tanggalnya
    protected $casts = [
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'service_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * 1. Cek apakah sedang diskon (Sudah ditambah logika Kalender)
     */
    public function getIsDiscountAttribute()
    {
        $now = now(); // Waktu saat ini

        // Syarat Diskon Aktif:
        // 1. Ada harga diskon & lebih kecil dari harga asli
        // 2. Tanggal sekarang berada di antara Start dan End
        $hasPrice = !is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
        
        $isInPeriod = true;
        if ($this->discount_start && $this->discount_end) {
            $isInPeriod = $now->between($this->discount_start, $this->discount_end);
        }

        return $hasPrice && $isInPeriod;
    }

    /**
     * 2. Ambil harga akhir (Otomatis berubah sesuai kalender)
     */
    public function getFinalPriceAttribute()
    {
        return $this->is_discount ? $this->discount_price : $this->price;
    }

    /**
     * 3. Hitung persentase diskon
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_discount || $this->price <= 0) {
            return 0;
        }
        
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}