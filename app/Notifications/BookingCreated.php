<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookingCreated extends Notification
{
    use Queueable;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function via($notifiable)
    {
        return ['database']; // simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->booking->user->name,
            'kendaraan' => $this->booking->vehicle_type,
            'layanan' => $this->booking->service->nama_layanan,
            'tanggal_booking' => $this->booking->tanggal_booking->format('d-m-Y H:i'),
        ];
    }
}