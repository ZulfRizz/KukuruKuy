<?php

namespace App\Events;

use App\Models\Order; // Jangan lupa import model Order
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // <-- PENTING!
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderCreated implements ShouldBroadcast // <-- Implementasikan interface ini
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order; // Buat property public agar bisa diakses di frontend

    public function __construct(Order $order) {
        $this->order = $order;
    }

    // Tentukan channel broadcast-nya
    public function broadcastOn(): array {
        // Kita broadcast ke channel private untuk semua admin
        // Nama channel bisa apa saja, contoh: 'admin-dashboard'
        return [
            new PrivateChannel('admin-dashboard'),
        ];
    }
}