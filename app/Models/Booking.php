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
        'notes',
        'complaint',
        'status',
        'mechanic_id',
        'payment_status',
        'queue_number',
    ];

    // Relasi ke customer (user yang booking)
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke User (alias)
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

    // Relasi ke Mechanic
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class, 'mechanic_id');
    }

    // Relasi ke Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Accessor Nama Kendaraan
    public function getVehicleNameAttribute()
    {
        if ($this->vehicle) {
            return $this->vehicle->brand . ' ' . $this->vehicle->plate_number;
        }
        return '-';
    }

    // --- TAMBAHAN BARU ---
    // Relasi ke ChatMessage (Sistem Chat)
    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }
}