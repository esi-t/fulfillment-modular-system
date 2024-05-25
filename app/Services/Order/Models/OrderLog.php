<?php

namespace App\Services\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $collection = 'OrderLogs';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
