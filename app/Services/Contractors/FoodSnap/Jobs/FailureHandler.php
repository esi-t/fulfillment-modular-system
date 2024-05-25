<?php

namespace App\Services\Contractors\FoodSnap\Jobs;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\FoodSnap\Enums\StatusesEnum;
use App\Services\Contractors\FoodSnap\FactoryMethod\OrderProcessor;
use App\Services\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FailureHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_ALLOWED_TO_RETRY = 3;

    public $timeout = 3600;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('high');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Order::query()
            ->where('failure_attempts', '<', static::MAX_ALLOWED_TO_RETRY)
            // with some other where clauses ...
            ->orderBy('created_at')
            ->cursor()
            ->each(fn ($order) => $this->recallWithExceptionHandler($order));
    }

    private function recallWithExceptionHandler(Order $order): void
    {
            try {
                $this->reCallBasedOnStatusCode($order);
            } catch (\Exception $exception) {
                Log::info('Failed on recalling food-snap failures : ' . $exception->getMessage());
            }

            $this->incrementAttempts($order);
    }

    private function reCallBasedOnStatusCode(Order $order): void
    {
        $methodName = $this->getMethodName($order->statusCode);

        (new OrderProcessor())->$methodName($order);
    }

    private function getMethodName(int $statusCode): string
    {
        $acknowledgeStatusCodes = [
            StatusesEnum::NewOrder->value,
            StatusesEnum::SentToStore->value,
        ];

        $acceptStatusCode = [
            StatusesEnum::Acknowledge->value
        ];

        if (in_array($statusCode, $acknowledgeStatusCodes)) {
            return 'acknowledgeTheOrder';
        } elseif (in_array($statusCode, $acceptStatusCode)) {
            return 'acceptTheOrder';
        }

        throw new \Exception('Method name is not defined');
    }

    private function incrementAttempts(Order $order): void
    {
        $order->increment('failure_attempts');
    }
}
