<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'service_id',
    'vehicle_id',
    'mechanic_id', // ✅ tambahkan baris ini
    'notes',
    'status',
    'booking_date',
];
}