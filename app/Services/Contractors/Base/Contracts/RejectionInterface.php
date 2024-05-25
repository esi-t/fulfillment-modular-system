<?php

namespace App\Services\Contractors\Base\Contracts;

use App\Services\Order\Models\Order;

interface RejectionInterface
{
    public function reject(Order $order): bool;
}
