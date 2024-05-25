<?php

namespace App\Services\Contractors\Digi\FactoryMethod;

use App\Services\Contractors\Base\Contracts\RejectionInterface;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\OrderProcessorInterface;
use App\Services\Contractors\Digi\Authentication\Authenticator;
use App\Services\Contractors\Digi\Enums\StatusesEnum;
use App\Services\Contractors\Digi\FactoryMethod\Steps\HasAccept;
use App\Services\Contractors\Digi\FactoryMethod\Steps\HasAcknowledge;
use App\Services\Contractors\Digi\FactoryMethod\Steps\HasPick;
use App\Services\Contractors\Digi\FactoryMethod\Steps\HasReject;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class OrderProcessor implements OrderProcessorInterface, RejectionInterface
{
    use HasAcknowledge;
    use HasPick;
    use HasAccept;
    use HasReject;

    protected Order $order;

    protected string $token;

    protected string $updateUrl;

    protected ?Order $lastOrder;

    public function __construct()
    {
        $this->token = Authenticator::token();

        $this->updateUrl = env('DIGI_BASE_URL');
    }

    public function process(array $newOrder): void
    {
        $this->boot($newOrder);

        $this->actBaseOnStatus($newOrder);
    }

    protected function actBaseOnStatus(array $newOrder): void
    {
        if ($this->isCancel($newOrder['statusCode'])) {
            $this->cancel($newOrder);
            return;
        }

        if ($this->isAmendment($newOrder['code'])) {
            $this->updateLastOrderAsAmendment();
        }

        $this->continueTheProcess($newOrder);
    }

    protected function isCancel(string $orderStatusCode): bool
    {
        return $orderStatusCode == StatusesEnum::Cancel->value;
    }

    protected function cancel(array $newOrder): void
    {
        $orders = Order::query()
            ->where('code', $newOrder['code'])
            ->where('channel_id', ChannelsEnum::Digi->value)
            ->orderByDesc('created_at')
            ->get();

        $lastOrder = $orders->first();

        $lastOrder->update([
            'statusCode' => StatusesEnum::Cancel->value,
            'internal_status' => OrderInternalStatusEnum::CanceledByContractor->value
        ]);
    }

    protected function isAmendment(string $orderCode): bool
    {
        $this->setLastOrder($orderCode);

        if (is_null($this->lastOrder))
            return false;

        // check for last condition ...
    }

    protected function updateLastOrderAsAmendment(): void
    {
        $this->lastOrder->update([
            'internal_status' => OrderInternalStatusEnum::HasAmendment->value
        ]);
    }

    protected function setLastOrder(string $orderCode): void
    {
        // set last order
    }

    protected function continueTheProcess(array $newOrder): void
    {
        $this->order = Order::create($newOrder);

        $this->acknowledge($this->order);
    }

    protected function boot(array &$newOrder): void
    {
        $this->convertPricesToToman($newOrder);

        $this->alignProducts($newOrder);
    }
}
