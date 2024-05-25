<?php

namespace App\Services\Contractors\Snap\FactoryMethod;

use App\Services\Contractors\Base\Contracts\RejectionInterface;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\OrderProcessorInterface;
use App\Services\Contractors\Snap\Authentication\Authenticator;
use App\Services\Contractors\Snap\Enums\StatusesEnum;
use App\Services\Contractors\Snap\FactoryMethod\Concerns\HasProductInspector;
use App\Services\Contractors\Snap\FactoryMethod\Steps\HasAccept;
use App\Services\Contractors\Snap\FactoryMethod\Steps\HasAcknowledge;
use App\Services\Contractors\Snap\FactoryMethod\Steps\HasNeedForCall;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;
use Illuminate\Support\Facades\Redis;

class OrderProcessor implements OrderProcessorInterface, RejectionInterface
{
    use HasAccept;
    use HasAcknowledge;
    use HasNeedForCall;
    use HasProductInspector;

    protected string $token;

    protected Order $order;

    public function process(array $newOrder): void
    {
        $this->boot($newOrder);

        $this->actBaseOnStatus($newOrder);
    }

    private function boot(array &$newOrder): void
    {
        // something was removed ...

        $this->mergeDuplicateProductsIfExist($newOrder);

        $newOrder['products'] = $this->coordinateProductsStructure($newOrder['products']);
    }

    protected function actBaseOnStatus(array $newOrder): void
    {
        if ($this->isCancel($newOrder['statusCode'])) {
            $this->cancel($newOrder);
            return;
        }

        if ($this->isAmendment($newOrder['code'], $newOrder['statusCode'])) {
            $this->amendment($newOrder);
            return;
        }

        $this->continueTheProcess($newOrder);
    }

    private function isCancel(int $statusCode): bool
    {
        return StatusesEnum::CanceledOrder->value == $statusCode;
    }

    private function cancel(array $newOrderData): void
    {
        $lastOrder = $this->getLastOrder($newOrderData['code']);

        $lastOrder->update([
            'statusCode' => StatusesEnum::CanceledOrder->value,
            'internal_status' => OrderInternalStatusEnum::CanceledByContractor->value,
        ]);
    }

    protected function isAmendment(string $orderCode, int $statusCode): bool
    {
        $lastOrder = $this->getLastOrder($orderCode);

        if (is_null($lastOrder)) {
            return false;
        }

        return $lastOrder->statusCode == StatusesEnum::NeedForCall->value &&
            ($statusCode == StatusesEnum::NewOrder->value || $statusCode == StatusesEnum::SentToStore->value);
    }

    protected function amendment(array $newOrder): void
    {
        $this->updateLastOrderAsAmendment($newOrder['code']);

        $this->continueTheProcess($newOrder);
    }

    protected function updateLastOrderAsAmendment(string $orderCode): void
    {
        // removed
    }

    protected function getLastOrder(string $orderCode): ?Order
    {
        // removed
    }

    protected function continueTheProcess(array $newOrder): void
    {
        $this->checkForDuplication($newOrder);

        $this->order = Order::query()
            ->create($newOrder);

        $this->notifyOrderSequences();
    }

    private function checkForDuplication(array $newOrder): void
    {
        $hashedOrder = hash('sha256', json_encode([$newOrder['code'], $newOrder['statusCode']]));

        $existence = Redis::eval(<<<'LUA'
            if redis.call('EXISTS', KEYS[1]) == 1 then
                return 1
            else
                redis.call('SET', KEYS[1], ARGV[1])
                redis.call('EXPIRE', KEYS[1], 5)
                return 0
            end
            LUA, 1, $hashedOrder, '1');

        if ((bool)$existence)
            throw new \Exception('Duplicate order for snap');
    }

    public function notifyOrderSequences(): void
    {
        $this->setToken();

        $this->acknowledgeTheOrder($this->order);
    }

    private function setToken(): static
    {
        $this->token = Authenticator::token($this->order->store_id);

        return $this;
    }

    private function ensureTokenIsSet(): void
    {
        if (!isset($this->token)) {
            $this->setToken();
        }
    }
}
