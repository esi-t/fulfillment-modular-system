<?php

namespace App\Services\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreName extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'my table';

    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'StoreID';

    public static function getStoreName(int $storeId): string
    {
        try {
            return StoreName::query()
                ->where('StoreID', $storeId)
                ->first()->Name ?? 'تعریف نشده';
        } catch (\Exception $e) {
            return 'تعریف نشده';
        }
    }
}
