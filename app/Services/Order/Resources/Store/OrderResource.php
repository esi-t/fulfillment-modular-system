<?php

namespace App\Services\Order\Resources\Store;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Order\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->_id,
            'code' => $this->code,
            'invoice_number' => $this->invoice_number,
            'price' => $this->price,
            'full_name' => $this->fullName,
            'delivery_address' => $this->deliverAddress,
            'comment' => $this->comment,
            'delivery_price' => $this->deliveryPrice,
            'packing_price' => $this->packingPrice,
            'delivery_time' => $this->deliveryTime,
            'preparation_time' => $this->preparationTime,
            'order_date' => $this->newOrderDate,
            'channel_id' => ChannelsEnum::toPersianString($this->channel_id),
            'internal_status' => $this->internal_status,
            'products' => Order::fetchSimilarBarcodes($this->products),
        ];
    }
}
