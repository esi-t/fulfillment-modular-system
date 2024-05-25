<?php

namespace App\Services\Contractors\Base\Contracts;

interface InvoiceInterface
{
    public function prepareData(array $data): array;
}
