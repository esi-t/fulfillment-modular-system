<?php

namespace App\Services\Contractors\Base\FactoryMethod;

use App\Services\Contractors\Aldy\FactoryMethod\ProductActivation as AldyActivation;
use App\Services\Contractors\Digi\FactoryMethod\ProductActivation as DigiActivation;
use App\Services\Contractors\FoodSnap\FactoryMethod\ProductActivation as FoodSnapActivation;
use App\Services\Contractors\Snap\FactoryMethod\ProductActivation as SnapActivation;

abstract class AbstractCreator
{
    abstract public function getImplementor(): OrderProcessorInterface;

    public function processOrder(array $newOrder): void
    {
        $orderProcessorObject = $this->getImplementor();

        $orderProcessorObject->process($newOrder);
    }

    public static function getActivators(): array
    {
        return [
            new SnapActivation(),
            new AldyActivation(),
            new DigiActivation(),
            new FoodSnapActivation(),
        ];
    }
}
