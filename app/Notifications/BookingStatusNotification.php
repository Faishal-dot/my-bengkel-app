<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingStatusNotification extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    // ⬇⬇ bagian ini yang kamu maksud
    public function toDatabase($notifiable)
    {
        return [
            'type'       => 'booking',
            'booking_id' => $this->booking->id,
            'vehicle'    => $this->booking->vehicle 
                                ? ($this->booking->vehicle->brand . ' ' . $this->booking->vehicle->plate_number)
                                : '-',
            'status'     => $this->booking->status,
            'tanggal'    => now()->format('d-m-Y H:i'),
        ];
    }
}