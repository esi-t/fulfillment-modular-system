<?php

namespace App\Services\Contractors\Digi\Jobs;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Contractors\Digi\FactoryMethod\OrderProcessor;
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
            // with other condition which were removed ...
            ->orderBy('created_at')
            ->cursor()
            ->each(fn($order) => $this->recallWithExceptionHandler($order));
    }

    private function recallWithExceptionHandler(Order $order): void
    {
        try {
            (new OrderProcessor())->accept($order);
        } catch (\Exception $exception) {
            Log::info('Failed on recalling digi failures : ' . $exception->getMessage());
        }

        $order->increment('failure_attempts');
    }
}
