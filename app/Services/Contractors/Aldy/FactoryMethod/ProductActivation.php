<?php

namespace App\Services\Contractors\Aldy\FactoryMethod;

use App\Services\Contractors\Base\FactoryMethod\ProductActivationInterface;

class ProductActivation implements ProductActivationInterface
{
    public function activate(array $products): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return 'آلدی';
    }
}
