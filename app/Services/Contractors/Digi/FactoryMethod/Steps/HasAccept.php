<?php

namespace App\Services\Contractors\Digi\FactoryMethod\Steps;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasAccept
{
    public function accept(Order $order): void
    {
        // call
    }

    protected function getAcceptData(): array
    {
        return [
            // some data
        ];
    }

    protected function logAccept(Response $response): void
    {
        // log into mongo
    }
}
