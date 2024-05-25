<?php

namespace App\Services\Contractors\Aldy\Enums;

enum StatusesEnum: int
{
    case NewOrder = 56;

    case Acknowledge = 61;

    case Pick = 713;

    case Accept = 42;

    case Nfc = 74;

    case Reject = 75;
}
