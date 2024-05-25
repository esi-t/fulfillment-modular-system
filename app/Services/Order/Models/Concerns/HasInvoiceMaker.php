<?php

namespace App\Services\Order\Models\Concerns;

use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Helpers\InvoiceApi;
use Illuminate\Support\Facades\Auth;

trait HasInvoiceMaker
{
    public function createInvoice(): ?string
    {
        if (! $this->canInvoice())
            throw new \Exception(__('order::messages.can_not_invoice'));

        $response = InvoiceApi::request($this->getAttributes());

        if ($response->failed())
            return null;

        $this->update([
            'internal_status' => OrderInternalStatusEnum::InvoicedInApiIntegration->value,
            'invoice_number' => $invoice = $response->json()['invoice_number']
        ]);

        return $invoice;
    }

    public function collected(): ?string
    {
        if (! $this->canCollect())
            throw new \Exception(__('order::messages.can_not_collect'));

        $this->update([
            'collector' => Auth::user()->toArray(),
            'internal_status' => OrderInternalStatusEnum::CollectedByStore->value,
        ]);

        return $this->createInvoice();
    }

    // using array keys because o(1) time complexity
    protected function canInvoice(): bool
    {
        $allowedStatuses = [
            OrderInternalStatusEnum::CollectedByStore->value => true,
        ];

        return array_key_exists($this->internal_status, $allowedStatuses);
    }

    // using array keys because o(1) time complexity
    protected function canCollect(): bool
    {
        $allowedStatuses = [
            OrderInternalStatusEnum::OpenedByStore->value => true,
            OrderInternalStatusEnum::CollectedByStore->value => true,
        ];

        return array_key_exists($this->internal_status, $allowedStatuses);
    }
}
