<?php

namespace App\Services\Contractors\Base\Enums;

use App\Services\Contractors\Aldy\Contracts\Invoice as AldyInovice;
use App\Services\Contractors\Base\Contracts\InvoiceInterface;
use App\Services\Contractors\Digi\Contracts\Invoice as DigiInvoice;
use App\Services\Contractors\Snap\Contracts\Invoice as SnapInvoice;
use App\Services\Contractors\FoodSnap\Contracts\Invoice as FoodSnapInvoice;

enum ChannelsEnum: int
{
    case Snap = 1;

    case Aldy = 2;

    case Digi = 3;

    case FoodSnap = 4;

    public static function toPersianString(?int $channelId = null): string
    {
        return match($channelId) {
            self::Snap->value => 'اسنپ',
            self::Aldy->value => 'آلدی',
            self::Digi->value => 'دیجی',
            self::FoodSnap->value => 'اسنپ فود',
            default => throw new \Exception('The channel you provided is not defined'),
        };
    }

    public static function toString(int $channelId): string
    {
        return match($channelId) {
            self::Snap->value => 'snap',
            self::Aldy->value => 'Aldy',
            self::Digi->value => 'Digi',
            self::FoodSnap->value => 'FoodSnap',
            default => throw new \Exception('The channel you provided is not defined'),
        };
    }

    public static function implementor(int $channelId): InvoiceInterface
    {
        return match($channelId) {
            self::Snap->value => new SnapInvoice(),
            self::Aldy->value => new AldyInovice(),
            self::Digi->value => new DigiInvoice(),
            self::FoodSnap->value => new FoodSnapInvoice(),
            default => throw new \Exception('Channel Id is not defined')
        };
    }
}
