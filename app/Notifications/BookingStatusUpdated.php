<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Booking;

class BookingStatusUpdated extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // kalau mau juga email tambahkan 'mail'
    }

    /**
     * Data yang disimpan ke tabel notifications (untuk ditampilkan di UI).
     */
    public function toDatabase($notifiable)
    {
        return [
            'type'       => 'booking',
            'booking_id' => $this->booking->id,
            'vehicle'    => $this->booking->vehicle ?? '-',
            'status'     => $this->booking->status,
            'tanggal'    => now()->format('d-m-Y H:i'),
        ];
    }

    /**
     * (Opsional) Jika pakai notifikasi via email.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Booking Anda Diperbarui')
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Booking dengan ID #{$this->booking->id} telah diperbarui.")
            ->line("Status terbaru: " . ucfirst($this->booking->status))
            ->action('Lihat Booking', url('/booking'))
            ->line('Terima kasih sudah menggunakan layanan kami!');
    }
}