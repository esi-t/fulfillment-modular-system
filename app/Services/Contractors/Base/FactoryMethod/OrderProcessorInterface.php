<?php

namespace App\Services\Contractors\Base\FactoryMethod;

interface OrderProcessorInterface
{
    public function process(array $newOrder): void;
}
