<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'price', 'discount_price', 'description'];

    // 1. Cek apakah sedang diskon
    public function getIsDiscountAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price < $this->price;
    }

    // 2. Ambil harga akhir (Harga yang dibayar customer)
    public function getFinalPriceAttribute()
    {
        return $this->is_discount ? $this->discount_price : $this->price;
    }

    // 3. Hitung persentase diskon untuk badge %
    public function getDiscountPercentageAttribute()
    {
        if (!$this->is_discount) return 0;
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}