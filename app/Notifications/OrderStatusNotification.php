<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // simpan ke database
    }

    public function toDatabase($notifiable)
    {
        return [
            'type'         => 'order',
            'order_id'     => $this->order->id,
            'product_name' => $this->order->product->name ?? '-',
            'status'       => ucfirst($this->order->status),
            'total_price'  => (int) $this->order->total_price, // pastikan numeric
            'total_price_rp' => 'Rp ' . number_format((int) $this->order->total_price, 0, ',', '.'),
            'tanggal'      => now()->format('d-m-Y H:i'),
        ];
    }
}
