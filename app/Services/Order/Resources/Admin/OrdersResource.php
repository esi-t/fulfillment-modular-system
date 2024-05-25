<?php

namespace App\Services\Order\Resources\Admin;

use App\Services\Contractors\Base\Enums\ChannelsEnum;
use App\Services\Contractors\Base\Helpers\StatusMapper;
use App\Services\Order\Models\StoreName;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource // TODO : Use OrderResource instead of this, before that check keys and be touch with frontend
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
            'store_id' => $this->store_id,
            'store_name' => StoreName::getStoreName($this->store_id),
            'status' => StatusMapper::map((int)$this->statusCode, (int)$this->channel_id),
            'internal_status' => $this->internal_status,
            'code' => $this->code,
            'full_name' => $this->fullName,
            'phone' => $this->phone,
            'order_date' => $this->newOrderDate,
            'collector' => $this->collector,
            'invoice_number' => $this->invoice_number,
            'channel_id' => ChannelsEnum::toPersianString($this->channel_id)
        ];
    }
}
