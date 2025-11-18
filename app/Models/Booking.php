<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'vehicle_id',
    'service_id',
    'booking_date',
    'status',
    'mechanic_id',
];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    // Relasi ke Vehicle
   public function vehicle()
{
    return $this->belongsTo(Vehicle::class, 'vehicle_id');
}

// ✅ Tambahin accessor
public function getVehicleNameAttribute()
{
    if ($this->vehicle) {
        return $this->vehicle->brand . ' ' . $this->vehicle->plate_number;
    }

    return '-';
}

public function mechanic()
{
    return $this->belongsTo(Mechanic::class, 'mechanic_id');
}

}