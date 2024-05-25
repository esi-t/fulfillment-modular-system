<?php

namespace App\Services\Order\Enums;

enum OrderInternalStatusEnum: int
{
    case None = 70;

    case OpenedByStore = 89;

    case NfcByStore = 90;

    case CollectedByStore = 91;

    case NfcByAdmin = 100;

    case InvoicedInApiIntegration = 200;

    case HasAmendment = 300;

    case CanceledByContractor = 501;
}
