<?php

namespace App\Services\Contractors\Digi\FactoryMethod\Steps;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasReject
{
    public function reject(Order $order): bool
    {
        // some logic
    }

    protected function getRejectData(): array
    {
        return [
            // some data
        ];
    }

    protected function logReject(Response $response): void
    {
        // log into mongo
    }
}
