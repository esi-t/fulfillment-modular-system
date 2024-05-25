<?php

namespace App\Services\Public\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Models\Order;
use App\Services\Public\Requests\InvoiceRequest;
use Illuminate\Support\Facades\Http;

class PublicController extends Controller
{
    public function updateInvoice(Order $order, InvoiceRequest $request)
    {
        $validatedData = $request->validated();

        $order->update([
            'invoice_number' => $validatedData['invoice']
        ]);

        if ($this->isAldy($order)) {
            dispatch(fn () => $this->updateAldyHub($order->code, $validatedData['invoice']));
        }


        return response()->json([], 204);
    }

    private function isAldy(Order $order): bool
    {
        return $order->channel_id == ChannelsEnum::Aldy->value;
    }

    private function updateAldyHub(string $orderCode, string $invoice): void
    {
        $url = env('ALDY_BASE_URL') . "status/orders/{$orderCode}/invoice_number/";

        Http::post($url, [
            'invoice_number' => $invoice
        ]);
    }
}
