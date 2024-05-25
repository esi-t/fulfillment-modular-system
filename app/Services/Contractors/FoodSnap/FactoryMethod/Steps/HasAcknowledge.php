<?php

namespace App\Services\Contractors\FoodSnap\FactoryMethod\Steps;

use App\Services\Order\Models\Order;


trait HasAcknowledge
{
    protected string $ackUrl;

    abstract function ensureTokenIsSet();

    public function acknowledgeTheOrder(Order $order): void
    {
        $this->order = $order;

        $this->ensureTokenIsSet();

        $this->notifyAcknowledge();
    }
}
