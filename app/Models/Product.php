<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'purchase_price', 'price', 'stock', 'sku', 'image'
    ];

    // Tambahkan ini untuk relasi ke Service
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_product')->withPivot('quantity');
    }
}