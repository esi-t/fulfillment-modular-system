<?php

namespace App\Services\Order\Resources\Store;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            'full_name' => $this->fullName,
            'order_date' => $this->newOrderDate,
            'channel_id' => ChannelsEnum::toPersianString($this->channel_id),
            'internal_status' => $this->internal_status,
        ];
    }
}
