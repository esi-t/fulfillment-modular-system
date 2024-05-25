<?php

namespace App\Services\Contractors\Digi\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Jobs\NewOrderProcessor;
use App\Services\Contractors\Digi\Exceptions\DigiException;
use App\Services\Contractors\Digi\Models\MapStore;
use App\Services\Contractors\Digi\Requests\OrderRequest;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DigiController extends Controller
{
    public function processOrder(OrderRequest $request): JsonResponse
    {
        try {
            $this->process($request->all());

            return response()->json([], Response::HTTP_ACCEPTED);
        } catch (DigiException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    protected function process(array $newOrder): void
    {
        $newOrder = array_merge($newOrder, [
            'store_id' => MapStore::map((string)$newOrder['storeCode']),
            'channel_id' => ChannelsEnum::Digi->value,
            'failure_attempts' => 0,
            'internal_status' => OrderInternalStatusEnum::None->value,
        ]);

        NewOrderProcessor::dispatch($newOrder);
    }
}
