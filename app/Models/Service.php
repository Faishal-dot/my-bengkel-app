<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'discount_price', 'description'];

    /**
     * Relasi ke model Product (Many-to-Many)
     * Menghubungkan layanan dengan produk yang digunakan dalam paket bundle.
     */
    public function products()
    {
        // Pastikan nama tabel pivot sesuai dengan migration Anda (umumnya 'service_product' atau 'product_service')
        return $this->belongsToMany(Product::class, 'service_product')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * 1. Cek apakah sedang diskon
     * Aksesor: $service->is_discount
     */
    public function getIsDiscountAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    /**
     * 2. Ambil harga akhir (Harga yang dibayar customer)
     * Aksesor: $service->final_price
     */
    public function getFinalPriceAttribute()
    {
        return $this->is_discount ? $this->discount_price : $this->price;
    }

    /**
     * 3. Hitung persentase diskon untuk badge %
     * Aksesor: $service->discount_percentage
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_discount || $this->price <= 0) {
            return 0;
        }
        
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}