<?php

namespace App\Services\Contractors\Snap\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Jobs\NewOrderProcessor;
use App\Services\Contractors\Snap\Requests\OrderRequest;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SnapFoodController extends Controller
{
    public function processOrder(int $storeId, OrderRequest $request): JsonResponse
    {
        $newOrder = array_merge($request->all(), [
            'store_id' => $storeId,
            'channel_id' => ChannelsEnum::Snap->value,
            'failure_attempts' => 0,
            'internal_status' => OrderInternalStatusEnum::None->value,
        ]);

        NewOrderProcessor::dispatch($newOrder);

        return response()->json([], Response::HTTP_ACCEPTED);
    }
}
