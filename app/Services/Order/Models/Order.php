<?php

namespace App\Services\Order\Models;

use App\Services\Order\Enums\OrderInternalStatusEnum;
use App\Services\Order\Models\Concerns\HasInvoiceMaker;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    use HasInvoiceMaker;

    protected $connection = 'mongodb';

    protected $collection = 'Orders';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static function fetchSimilarBarcodes(array $products): array
    {
        return array_map(function ($product) {
            $product['barcode'] = LinkBarcode::getBarcodes($product['barcode']) ?? [];
            $product['quantity'] = (int)$product['quantity'];
            $product['price'] = (int)$product['price'];
            $product['vat'] = (int)$product['vat'];
            $product['discount'] = $product['price'] - (int)$product['discount'];
            $product['uuid'] = Str::uuid();

            return $product;
        }, $products);
    }

    public function isNew(): bool
    {
        return $this->internal_status == OrderInternalStatusEnum::None->value;
    }
}
