<?php

namespace App\Services\Panel\Repositories;

use App\Services\Order\Models\Order;
use App\Services\Panel\Models\User;
use Illuminate\Http\Client\Response;

interface AdminServiceInterface
{
    public function getOrders(array $parameters = []);

    public function makeOrderNfc(Order $order): bool;

    public function getUsers(array $parameters = []);

    public function createUser(array $parameters): User;

    public function updateUser(array $parameters): User;
}
