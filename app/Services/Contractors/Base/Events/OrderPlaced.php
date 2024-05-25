<?php

namespace App\Services\Contractors\Base\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queue = 'high';

    public function __construct(public $message, public $storeId)
    {}

    public function broadcastOn()
    {
        return [
            new PrivateChannel('orders.'.(string)$this->storeId)
        ];
    }

    public function broadcastAs()
    {
        return 'my-events';
    }

    public function broadcastWith(): array
    {
        return ['storeId' => $this->storeId];
    }
}

