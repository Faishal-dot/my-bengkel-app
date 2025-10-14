<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // bisa tambahkan 'mail' kalau mau email
    }

    /**
     * Data yang disimpan ke tabel notifications (untuk ditampilkan di UI).
     */
    public function toDatabase($notifiable)
    {
        return [
            'type'         => 'order',
            'order_id'     => $this->order->id,
            'product_name' => $this->order->product->name ?? '-',
            'status'       => $this->order->status,
            'total_price'  => $this->order->total_price,
            'tanggal'      => now()->format('d-m-Y H:i'),
        ];
    }

    /**
     * (Opsional) Jika pakai notifikasi via email.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Pesanan Anda Diperbarui')
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Pesanan dengan ID #{$this->order->id} telah diperbarui.")
            ->line("Produk: " . ($this->order->product->name ?? '-'))
            ->line("Status terbaru: " . ucfirst($this->order->status))
            ->line("Total Harga: Rp " . number_format($this->order->total_price, 0, ',', '.'))
            ->action('Lihat Pesanan', url('/customer/pesanan-saya'))
            ->line('Terima kasih sudah berbelanja di Bengkel Anjay!');
    }
}