<?php

namespace App\Services\Contractors\Base\FactoryMethod;

// TODO : should be in contracts folder
interface ProductActivationInterface
{
    public function activate(array $products): bool;

    public function __toString(): string;
}
