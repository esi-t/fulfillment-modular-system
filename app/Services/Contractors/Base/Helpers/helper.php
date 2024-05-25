<?php

use App\Services\Contractors\Base\FactoryMethod\AbstractCreator;

if (!function_exists('processOrder')) {
    function processOrder(AbstractCreator $creator, array $newOrder): void {
        $creator->processOrder($newOrder);
    }
}
