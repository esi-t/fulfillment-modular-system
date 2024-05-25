<?php

namespace App\Services\Contractors\Aldy\Jobs;

use App\Services\Contractors\Aldy\Enums\StatusesEnum;
use App\Services\Contractors\Aldy\FactoryMethod\OrderProcessor;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FailureHandler implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const MAX_ALLOWED_TO_RETRY = 3;

    public $timeout = 3600;

    public $tries = 1;

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
            // with other conditions which were removed
            ->orderBy('created_at')
            ->cursor()
            ->each(fn ($order) => $this->recallWithExceptionHandler($order));
    }

    private function recallWithExceptionHandler(Order $order): void
    {
        try {
            $this->reCallBasedOnStatusCode($order);
        } catch (\Exception $exception) {
            //
        }

        $order->increment('failure_attempts');
    }

    private function reCallBasedOnStatusCode(Order $order): void
    {
        (new OrderProcessor())->acceptTheOrder($order);
    }
}
