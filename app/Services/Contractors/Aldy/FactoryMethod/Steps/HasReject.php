<?php

namespace App\Services\Contractors\Aldy\FactoryMethod\Steps;

use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait HasReject
{
    private string $rejectUrl;

    abstract function ensureTokenIsSet();

    public function reject(Order $order): bool
    {
        // some logic
    }
}
