<?php

namespace App\Services\Contractors\Snap\FactoryMethod\Steps;

use App\Services\Order\Models\Order;

trait HasAccept
{
    protected string $acceptUrl;

    abstract function ensureTokenIsSet();

    public function acceptTheOrder(Order $order): void
    {
        $this->order = $order;

        $this->ensureTokenIsSet();

        $this->notifyAccept();
    }
}
