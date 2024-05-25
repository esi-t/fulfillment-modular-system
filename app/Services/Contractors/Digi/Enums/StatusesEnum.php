<?php

namespace App\Services\Contractors\Digi\Enums;

enum StatusesEnum: string
{
    case New = 'new';

    case Acknowledge = 'ack';

    case Pick = 'pick';

    case Accept = 'accept';

    case Reject = 'reject';

    case Edit = 'edit';

    case Cancel = 'cancel';

    case Done = 'done';
}
