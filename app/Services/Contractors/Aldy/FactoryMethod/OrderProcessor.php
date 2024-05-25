<?php

namespace App\Services\Contractors\Aldy\FactoryMethod;

use App\Services\Contractors\Aldy\Authentication\Authenticator;
use App\Services\Contractors\Aldy\FactoryMethod\Steps\HasAccept;
use App\Services\Contractors\Aldy\FactoryMethod\Steps\HasAcknowledge;
use App\Services\Contractors\Aldy\FactoryMethod\Steps\HasPick;
use App\Services\Contractors\Aldy\FactoryMethod\Steps\HasReject;
use App\Services\Contractors\Base\Contracts\RejectionInterface;
use App\Services\Contractors\Base\FactoryMethod\OrderProcessorInterface;
use App\Services\Order\Models\Order;

class OrderProcessor implements OrderProcessorInterface, RejectionInterface
{
    use HasAcknowledge;
    use HasPick;
    use HasAccept;
    use HasReject;

    protected string $token;

    protected Order $order;

    public function process(array $newOrder): void
    {
        $this->boot($newOrder);

        $this->notifyOrderSequences($newOrder);
    }

    private function boot(array &$newOrder): void
    {
        try {
            $newOrder['statusCode'] = (int)$newOrder['statusCode']; // type casting for our enums
        } catch (\Exception $exception) {

        }
    }

    private function notifyOrderSequences(array $newOrder): void
    {
        $this->order = Order::query()
            ->create($newOrder);

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
