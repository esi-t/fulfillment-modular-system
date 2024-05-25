<?php

namespace App\Services\Contractors\Digi\Contracts;

use App\Services\Contractors\Base\Contracts\InvoiceInterface;
use App\Services\Contractors\Base\Enums\ChannelsEnum;
use Carbon\Carbon;

class Invoice implements InvoiceInterface
{
    public function prepareData(array $data): array
    {
        return [
            'core_id' => (string)$data['_id'],
            'code' => $data['code'],
            'comment' => 'default',
            'deliverAddress' => $data['deliverAddress'],
            'deliveryPrice' => '0',
            'deliveryTime' => '45',
            'firstName' => $data['firstName'],
            'fullName' => $data['fullName'],
            'lastName' => $data['lastName'],
            'newOrderDate' => Carbon::parse($data['newOrderDate'])->format('Y/m/d H:i:s'),
            'orderDate' => $data['newOrderDate'],
            'orderPaymentTypeCode' => 'ONLINE',
            'packingPrice' => 2200,
            'phone' => $data['phone'],
            'preparationTime' => $data['preparationTime'],
            'price' => $data['price'],
            'products' => $data['products'],
            'statusCode' => $data['statusCode'],
            'userCode' => 'wdw8eg8',
            'vendorCode' => '11',
            'channel' => ChannelsEnum::toString($data['channel_id']),
            'storeId' => $data['store_id'],
        ];
    }
}
