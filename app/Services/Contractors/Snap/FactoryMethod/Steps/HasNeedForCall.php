<?php

namespace App\Services\Contractors\Snap\FactoryMethod\Steps;

use App\Services\Order\Models\Order;

trait HasNeedForCall
{
    private string $rejectUrl;

    abstract function ensureTokenIsSet();

    public static function NeedForCall(Order $order): bool
    {
        return (new static())->reject($order);
    }

    public function reject(Order $order): bool
    {
        $this->order = $order;

        $this->ensureTokenIsSet();

        return $this->notifyReject();
    }
}
