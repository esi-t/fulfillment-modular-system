<?php

namespace App\Services\Panel\Controllers\Concerns;

use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Aldy\FactoryMethod\OrderProcessor;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Models\Order;

trait HasBeforeFinalizing
{
    public function beforeFinalizingOrder(Order $order): void
    {
        match ($order->channel_id) {
            ChannelsEnum::Aldy->value => $this->aldyBeforeCollecting($order),
            ChannelsEnum::FoodSnap->value => $this->foodSnapBeforeCollecting($order),
            default => null,
        };
    }

    protected function aldyBeforeCollecting(Order $order): void
    {
        // TODO : should be fixed, after checking can collect should be called
        if ($this->isAcceptedAlreadyInAldy($order->statusCode)) {
            return;
        }

        OrderProcessor::accept($order);
    }

    protected function foodSnapBeforeCollecting(Order $order): void
    {
        // TODO : should be fixed, after checking can collect should be called
        if ($this->isAcceptedAlreadyInAldy($order->statusCode)) {
            return;
        }

        \App\Services\Contractors\FoodSnap\FactoryMethod\OrderProcessor::accept($order);
    }

    protected function isAcceptedAlreadyInAldy(int $orderStatusCode): bool
    {
        return $orderStatusCode == StatusesEnum::Accept->value;
    }
}
