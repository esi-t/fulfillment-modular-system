<?php

namespace App\Services\Contractors\Aldy\FactoryMethod\Steps;

use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasAcknowledge
{
    private string $acknowledgeUrl;

    abstract function ensureTokenIsSet();

    public function acknowledgeTheOrder(Order $order): void
    {
        // some logic
    }
}
