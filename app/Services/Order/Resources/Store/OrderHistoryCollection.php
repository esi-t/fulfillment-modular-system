<?php

namespace App\Services\Order\Resources\Store;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderHistoryCollection extends ResourceCollection
{
    public $collects = OrderHistoryResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
