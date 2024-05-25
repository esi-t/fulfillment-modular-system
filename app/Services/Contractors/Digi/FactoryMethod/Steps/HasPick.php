<?php

namespace App\Services\Contractors\Digi\FactoryMethod\Steps;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasPick
{
    public function pick(Order $order): void
    {
        $this->order = $order;

        $this->callPick();

        dispatch(
            fn () => $this->accept($this->order)
        )->onQueue('high')->delay(5);
    }

    protected function callPick(): void
    {
        // call
    }

    protected function getPickData(): array
    {
        return [
            // some data
        ];
    }

    protected function logPick(Response $response): void
    {
        // log into mongo
    }
}
