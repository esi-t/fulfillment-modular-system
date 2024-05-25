<?php

namespace App\Services\Contractors\FoodSnap\FactoryMethod\Concerns;

use App\Services\Contractors\FoodSnap\Models\ProductMapper;
use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Order;

trait HasProductInspector
{
    protected function coordinateProductsStructure(array $products): array
    {
        if (empty($products)){
            return [];
        }

        return array_map(function ($product) {
            $product['price'] = $product['originPrice'];
            $product['discount'] = $product['price'] - $product['productDiscountVendorShare'];
            $product['barcode'] = ProductMapper::getBarcode((string)$product['barcode']);

            return $product;
        }, $products);
    }

    protected function mergeDuplicateProductsIfExist(array &$newOrder): void
    {
        if (!$this->hasDuplicateProducts($newOrder['products'])) {
            return;
        }

        if ($this->areDuplicatePricesDifferent($newOrder['products'])) {
            $this->stopTheProcess($newOrder);
        }

        $newOrder['products'] = $this->mergeDuplicateProducts($newOrder['products']);
    }

    protected function stopTheProcess(array $newOrder): void
    {
        $order = Order::query()
            ->create($newOrder);

        $snapIntegrationForNfc = $this->reject($order);

        if (!$snapIntegrationForNfc) {
            $this->changeOrderStatusManuallyForNfc($newOrder['code']);
        }

        throw new \Exception('Prices were different');
    }

    protected function changeOrderStatusManuallyForNfc(string $orderCode): void
    {
        Order::query()
            ->where('code', $orderCode)
            ->update([
                'internal_status' => OrderInternalStatusEnum::NfcByStore->value,
                'store_description' => 'مغایرت قیمت در کالاهای تکراری' // TODO : Should be translator
            ]);
    }

    protected function hasDuplicateProducts(array $products): bool
    {
        return collect($products)
            ->duplicates('barcode')
            ->isNotEmpty();
    }

    protected function areDuplicatePricesDifferent(array $products): bool
    {
        $products = collect($products);

        foreach ($products->groupBy('barcode') as $group) {
            if ($group->count() <= 1) {
                continue;
            }

            $uniquePricesCount = $group->pluck('price')
                ->unique()
                ->count();

            if ($uniquePricesCount > 1) {
                return true;
            }
        }

        return false;
    }

    protected function mergeDuplicateProducts(array $products): array
    {
        return collect($products)
            ->groupBy('barcode')
            ->map(function ($grouped) {
                if ($grouped->count() <= 1) {
                    return $grouped->first();
                }

                return [
                    'id' => $grouped->first()['id'],
                    'quantity' => $grouped->sum('quantity'),
                    'price' => $grouped->first()['price'],
                    'title' => $grouped->first()['title'],
                    'discount' => $grouped->sum('discount'),
                    'vat' => $grouped->first()['vat'],
                    'barcode' => $grouped->first()['barcode'],
                ];
            })->values()->toArray();
    }
}
