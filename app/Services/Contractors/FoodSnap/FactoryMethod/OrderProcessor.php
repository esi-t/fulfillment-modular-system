<?php

namespace App\Services\Contractors\FoodSnap\FactoryMethod;

use App\Services\Contractors\Base\Contracts\RejectionInterface;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\FactoryMethod\OrderProcessorInterface;
use App\Services\Contractors\FoodSnap\Authentication\Authenticator;
use App\Services\Contractors\FoodSnap\Enums\StatusesEnum;
use App\Services\Contractors\FoodSnap\FactoryMethod\Concerns\HasProductInspector;
use App\Services\Contractors\FoodSnap\FactoryMethod\Steps\HasAccept;
use App\Services\Contractors\FoodSnap\FactoryMethod\Steps\HasAcknowledge;
use App\Services\Contractors\FoodSnap\FactoryMethod\Steps\HasNeedForCall;
use App\Services\Contractors\FoodSnap\FactoryMethod\Steps\HasPick;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;

class OrderProcessor implements OrderProcessorInterface, RejectionInterface
{
    use HasAccept;
    use HasAcknowledge;
    use HasPick;
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

        $newOrder['products'] = $this->coordinateProductsStructure($newOrder['products']);

        $this->mergeDuplicateProductsIfExist($newOrder);
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
        // removed
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
        $this->order = Order::query()
            ->create($newOrder);

        $this->notifyOrderSequences();
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
