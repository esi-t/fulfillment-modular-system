<?php

namespace App\Services\Contractors\Base\Jobs;

use App\Services\Contractors\Aldy\FactoryMethod\Creator as AldyCreator;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\AbstractCreator;
use App\Services\Contractors\Base\Models\CashAddress;
use App\Services\Contractors\Digi\FactoryMethod\Creator as DigiCreator;
use App\Services\Contractors\FoodSnap\FactoryMethod\Creator as FoodSnapCreator;
use App\Services\Contractors\Snap\FactoryMethod\Creator as SnapCreator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class NewOrderProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected array $newOrder)
    {
        $this->onQueue('high');
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->notifyStores();

        $orderProcessorObject = $this->resolveOrderProcessor($this->newOrder['channel_id']);

        processOrder($orderProcessorObject, $this->newOrder);
    }

    private function notifyStores(): void
    {
        try {
            Redis::connection('pusher')->publish("channel:store:{$this->newOrder['store_id']}", 'newOrder');
        } catch (\Exception $exception) {

        }

        try {
            Http::get(CashAddress::notify($this->newOrder['store_id']));
        } catch (\Exception $exception) {

        }
    }

    private function resolveOrderProcessor(int $channelId): AbstractCreator
    {
        return match ($channelId) {
            ChannelsEnum::Snap->value => new SnapCreator(),
            ChannelsEnum::Aldy->value => new AldyCreator(),
            ChannelsEnum::Digi->value => new DigiCreator(),
            ChannelsEnum::FoodSnap->value => new FoodSnapCreator(),
        };
    }
}
