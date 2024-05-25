<?php

namespace App\Services\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkBarcode extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'my table';

    public $timestamps = false;

    public $incrementing = false;

    public static function getBarcodes(string $barcode): array
    {
        $linkBarcode = LinkBarcode::query()
            ->where('barcode', $barcode)
            ->first()
            ?->allbarcode;

        return empty(explode(';', $linkBarcode)[0]) ? (array)$barcode : array_filter(explode(';', $linkBarcode));
    }
}
