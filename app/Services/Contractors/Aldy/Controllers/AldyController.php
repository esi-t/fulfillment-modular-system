<?php

namespace App\Services\Contractors\Aldy\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Aldy\Requests\OrderRequest;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Jobs\NewOrderProcessor;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class AldyController extends Controller
{
    public function processOrder(int $storeId, OrderRequest $request)
    {
        $newOrder = array_merge($request->all(), [
            'store_id' => $storeId,
            'channel_id' => ChannelsEnum::Aldy->value,
            'failure_attempts' => 0,
            'internal_status' => OrderInternalStatusEnum::None->value,
            ]);

        NewOrderProcessor::dispatch($newOrder);

        return response()->json([], Response::HTTP_ACCEPTED);
    }

    public function changeStatus(string $orderCode)
    {
        $lastOrder = Order::query()
            ->where('code', $orderCode)
            ->where('channel_id', ChannelsEnum::Aldy->value)
            ->orderByDesc('created_at')
            ->first();

        if (is_null($lastOrder)) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        $lastOrder->update([
            'statusCode' => StatusesEnum::Reject->value,
            'internal_status' => OrderInternalStatusEnum::CanceledByContractor->value
        ]);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
