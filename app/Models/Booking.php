<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'service_id',
        'mechanic_id',
        'booking_date',
        'status',
        'payment_status',
        'queue_number',
        'notes',
        'complaint',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_price',
    ];

    protected static function booted()
    {
        static::deleting(function ($booking) {
            if ($booking->isForceDeleting()) {
                $booking->payment()?->forceDelete();
            } else {
                $booking->payment()?->delete();
            }
        });

        static::restoring(function ($booking) {
            $booking->payment()?->restore();
        });
    }

    /**
     * Relasi ke ChatMessage
     * Nama fungsi ini harus 'messages' agar sesuai dengan 'with(messages)' di Controller
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'booking_id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function service(): BelongsTo { return $this->belongsTo(Service::class); }
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
    public function mechanic(): BelongsTo { return $this->belongsTo(Mechanic::class); }
}