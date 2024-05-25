<?php

namespace App\Services\Panel\Repositories;

use App\Services\Order\Models\Order;

interface StoreServiceInterface
{
    public function getOrders(int $storeId, array $parameters);

    public function makeOrderNfc(Order $order, array $data);

    public function getOrdersHistory(array $parameters);

    public function getDataForPrint(Order $order);

    public function updateProductsActivation(array $products): array;

    public function openTheOrder(Order $order): void;
}
