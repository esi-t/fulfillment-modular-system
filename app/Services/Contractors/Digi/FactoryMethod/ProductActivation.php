<?php

namespace App\Services\Contractors\Digi\FactoryMethod;

use App\Services\Contractors\Base\FactoryMethod\ProductActivationInterface;
use App\Services\Contractors\Digi\Authentication\Authenticator;
use App\Services\Contractors\Digi\Models\MapStore;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ProductActivation implements ProductActivationInterface
{
    public function activate(array $products): bool
    {
        $data = $this->prepareData($products);

        $response = Http::withHeaders(['Authorization' => Authenticator::token()])
            ->post($this->url(), $data);

        if ($this->_isSuccessful($response))
            return true;

        return false;
    }

    private function prepareData(array $data): array
    {
        $storeCode = MapStore::mapFromDailyToDigi(
            Auth::user()->store_id
        );

        return [
            'storeCode' => $storeCode,
            'products' => array_map(
                fn($item) => ['barcode' => $item['barcode'], 'is_active' => $item['active']],
                $data
            )
        ];
    }

    private function _isSuccessful(Response $response): bool
    {
        if ($response->failed())
            return false;

        // TODO : check for rest of it ...
        return true;
    }

    private function url(): string
    {
        return env('url');
    }

    public function __toString(): string
    {
        return 'دیجی';
    }
}
