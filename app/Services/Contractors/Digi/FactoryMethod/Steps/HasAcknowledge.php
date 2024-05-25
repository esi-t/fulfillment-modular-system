<?php

namespace App\Services\Contractors\Digi\FactoryMethod\Steps;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasAcknowledge
{
    public function acknowledge(Order $order): void
    {
        $this->order = $order;

        $this->callAck();

        dispatch(
            fn () => $this->pick($this->order)
        )->onQueue('high')->delay(5);
    }

    protected function callAck(): void
    {
        // call
    }

    protected function getAckData(): array
    {
        return [
            // some data
        ];
    }

    protected function logAck(Response $response): void
    {
        // log into mongo
    }
}
