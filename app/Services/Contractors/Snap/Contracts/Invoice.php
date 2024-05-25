<?php

namespace App\Services\Contractors\Snap\Contracts;

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
            'deliveryPrice' => $data['deliveryPrice'],
            'deliveryTime' => $data['deliveryTime'],
            'firstName' => $data['firstName'],
            'fullName' => $data['fullName'],
            'lastName' => $data['lastName'],
            'newOrderDate' => Carbon::parse($data['newOrderDate'])->format('Y/m/d H:i:s'),
            'orderDate' => $data['orderDate'],
            'orderPaymentTypeCode' => 'ONLINE',
            'packingPrice' => $data['packingPrice'],
            'phone' => $data['phone'],
            'preparationTime' => $data['preparationTime'],
            'price' => $data['price'],
            'products' => $this->products($data['products']),
            'statusCode' => $data['statusCode'],
            'userCode' => $data['userCode'],
            'vendorCode' => $data['vendorCode'],
            'channel' => ChannelsEnum::toString($data['channel_id']),
            'storeId' => $data['store_id'],
        ];
    }

    protected function products(array $products): array
    {
        return array_map(function ($product) {
            $product['price'] = floor($product['price']);

            return $product;
        }, $products);
    }
}
