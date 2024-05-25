<?php

namespace App\Services\Contractors\Base\Jobs;

use App\Services\Contractors\Aldy\FactoryMethod\OrderProcessor as AldyOrderProcessor;
use App\Services\Contractors\Base\Contracts\RejectionInterface;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Digi\FactoryMethod\OrderProcessor as DigiOrderProcessor;
use App\Services\Contractors\FoodSnap\FactoryMethod\OrderProcessor as FoodSnapOrderProcessor;
use App\Services\Contractors\Snap\FactoryMethod\OrderProcessor as SnapOrderProcessor;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use App\Services\Panel\Repositories\HasSharedRepositoryFunctions;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RejectRecaller implements ShouldQueue
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
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Order::query()
            // some where clauses
            ->orderBy('created_at')
            ->cursor()
            ->each(fn ($order) => $this->recallWithExceptionHandler($order));
    }

    private function recallWithExceptionHandler(Order $order): void
    {
        try {
            $this->recallBaseOnChannel($order);
        } catch (\Exception $exception) {
            //
        }

        $order->increment('failure_attempts');
    }

    private function recallBaseOnChannel(Order $order): void
    {
        $rejectionService = $this->resolveReject($order->channel_id);

        $rejectionService->reject($order);
    }

    /**
     * @see HasSharedRepositoryFunctions
     * TODO : They should be in one and we should have an array which return all of these ...
     */
    private function resolveReject(int $channelId): RejectionInterface
    {
        return match ($channelId) {
            ChannelsEnum::Snap->value => new SnapOrderProcessor(),
            ChannelsEnum::Aldy->value => new AldyOrderProcessor(),
            ChannelsEnum::Digi->value => new DigiOrderProcessor(),
            ChannelsEnum::FoodSnap->value => new FoodSnapOrderProcessor(),
        };
    }
}
