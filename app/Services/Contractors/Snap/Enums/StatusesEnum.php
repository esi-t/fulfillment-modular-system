<?php

namespace App\Services\Contractors\Snap\Enums;

enum StatusesEnum: int
{
    case NewOrder = 56;

    case SentToStore = 714;

    case Acknowledge = 61;

    case OpenedByStore = 713;

    case AcceptedOrder = 42;

    case NeedForCall = 51;

    case CanceledOrder = 54;

    case NeedForMorePayment = 71;

    case NeedForCallByClient = 801;
}
