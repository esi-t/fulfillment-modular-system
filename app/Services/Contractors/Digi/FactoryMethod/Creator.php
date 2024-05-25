<?php

namespace App\Services\Contractors\Digi\FactoryMethod;

use App\Services\Contractors\Base\FactoryMethod\AbstractCreator;
use App\Services\Contractors\Base\FactoryMethod\OrderProcessorInterface;

class Creator extends AbstractCreator
{
    public function getImplementor(): OrderProcessorInterface
    {
        return app()->make(OrderProcessor::class);
    }
}
