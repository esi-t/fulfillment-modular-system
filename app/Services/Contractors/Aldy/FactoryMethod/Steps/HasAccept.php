<?php

namespace App\Services\Contractors\Aldy\FactoryMethod\Steps;

use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

trait HasAccept
{
    private string $acceptUrl;

    abstract function ensureTokenIsSet();

    public static function accept(Order $order): void
    {
        (new static())->acceptTheOrder($order);
    }

    public function acceptTheOrder(Order $order): Response
    {
        // some logic
    }
}
